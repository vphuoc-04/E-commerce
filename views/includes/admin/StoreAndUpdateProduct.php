<?php
// views/includes/admin/StoreAndUpdateProduct.php

require_once __DIR__ . '/../../customs/CustomInput.php';
require_once __DIR__ . '/../../customs/CustomDropBoxMenu.php';
require_once __DIR__ . '/../../customs/CustomButton.php';
require_once __DIR__ . '/../../customs/CustomForm.php';
require_once __DIR__ . '/../../customs/CustomSheet.php';
require_once __DIR__ . '/../../customs/CustomAlert.php';

require_once __DIR__ . '/../../../validations/ProductValidation.php';

// Kiểm tra xem có đang ở chế độ edit không từ URL
$isEditMode = isset($_GET['edit']) && !empty($_GET['edit']);
$productId = $isEditMode ? $_GET['edit'] : null;
$productData = null;

// Nếu là edit mode, lấy dữ liệu sản phẩm từ API
if ($isEditMode && $productId) {
    $apiUrl = "http://localhost/webbanhang/apis/ProductApi.php?route=show&id=" . $productId;
    $context = stream_context_create([
        'http' => ['ignore_errors' => true]
    ]);
    $response = file_get_contents($apiUrl, false, $context);
    
    if ($response !== false) {
        $result = json_decode($response, true);
        if ($result && $result['status'] === 'success') {
            $productData = $result['data'];
        }
    }
}

// Map category options if provided
$categoryOptions = $categoryOptions ?? [];
$validationWithOptions = array_map(function($field) use ($categoryOptions) {
    if ($field['type'] === 'select' && $field['name'] === 'category_id') {
        $field['options'] = $categoryOptions;
    }
    return $field;
}, $productValidation);

$children = [];

// Thêm hidden field cho ID - QUAN TRỌNG: Luôn có ID field
$children[] = '<input type="hidden" name="id" value="' . ($productData['id'] ?? '') . '">';

foreach($validationWithOptions as $field){
    // Lấy giá trị từ productData nếu có
    $value = $productData[$field['name']] ?? '';
    
    $children[] = '<div class="field-group">';
    $children[] = '<span class="field-label">' . preg_replace('/\s*\*$/', '', $field['label']) . '</span>';
    
    if (in_array($field['type'], ['text'])) {
        $children[] = new CustomInput(
            type: $field['type'],
            class: 'customer-input',
            placeholder: $field['label'],
            name: $field['name'],
            value: $value,
            required: isset($field['rules']['required']),
            attributes: [
                'pattern' => $field['rules']['pattern']['value'] ?? null,
                'title' => $field['rules']['pattern']['message'] ?? null,
                'minlength' => $field['rules']['minlength']['value'] ?? null,
                'maxlength' => $field['rules']['maxlength']['value'] ?? null
            ]
        );
    } elseif ($field['type'] === 'select') {
        $children[] = new CustomDropBoxMenu(
            class: 'user-drop-box-menu',
            name: $field['name'],
            options: $field['options'] ?? [],
            selected: $value,
            required: isset($field['rules']['required']),
            placeholder: $field['placeholder'] ?? 'Chọn...'
        );
    } elseif ($field['type'] === 'file') {
        // File input: render basic input with accept attr
        $children[] = '<input type="file" class="customer-input" name="image" accept="image/*">';
        
        // Hiển thị ảnh hiện tại nếu có (trong chế độ edit)
        if ($isEditMode && !empty($productData['image'])) {
            $imageUrl = "http://localhost/webbanhang/" . $productData['image'];
            $children[] = '<div class="current-image" style="margin-top: 8px;">';
            $children[] = '<img src="' . $imageUrl . '" alt="Current Image" style="width: 80px; height: 80px; object-fit: cover; border-radius: 8px;">';
            $children[] = '<p style="font-size: 12px; color: #666; margin-top: 4px;">Ảnh hiện tại</p>';
            $children[] = '</div>';
        }
    }
    $children[] = '</div>';
}

// Thay đổi text button dựa trên chế độ
$buttonText = $isEditMode ? "Cập nhật" : "Thêm mới";
$children[] = new CustomButton($buttonText, false, true, "user-button-confirm");

$alert = new CustomAlert('custom-alert', '', 'top-right');

$form = new CustomForm(
    children: $children,
    class: 'user-form',
    action: 'http://localhost/webbanhang/apis/ProductApi.php?route=save',
    method: 'POST',
    enctype: 'multipart/form-data'
);

// Thay đổi title và description dựa trên chế độ
$sheetTitle = $isEditMode ? 'CẬP NHẬT SẢN PHẨM' : 'THÊM SẢN PHẨM';
$sheetDescription = $isEditMode ? 'Cập nhật thông tin sản phẩm.' : 'Nhập thông tin sản phẩm để thêm mới.';

$sheet = new CustomSheet(
    children: [$alert, $form],
    class: 'user-sheet',
    title: $sheetTitle,
    description: $sheetDescription
);

echo $sheet->render([]);
?>