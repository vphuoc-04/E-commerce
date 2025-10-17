<?php

require_once __DIR__ . '/../../customs/CustomInput.php';
require_once __DIR__ . '/../../customs/CustomDropBoxMenu.php';
require_once __DIR__ . '/../../customs/CustomButton.php';
require_once __DIR__ . '/../../customs/CustomForm.php';
require_once __DIR__ . '/../../customs/CustomSheet.php';
require_once __DIR__ . '/../../customs/CustomAlert.php';

require_once __DIR__ . '/../../../validations/ProductCategoryValidation.php';

// Kiểm tra xem có đang ở chế độ edit không từ URL
$isEditMode = isset($_GET['edit']) && !empty($_GET['edit']);
$categoryId = $isEditMode ? $_GET['edit'] : null;
$categoryData = null;

// Nếu là edit mode, lấy dữ liệu danh mục từ API
if ($isEditMode && $categoryId) {
    $apiUrl = "http://localhost/webbanhang/apis/ProductCategoryApi.php?route=show&id=" . $categoryId;
    $context = stream_context_create([
        'http' => ['ignore_errors' => true]
    ]);
    $response = file_get_contents($apiUrl, false, $context);
    
    if ($response !== false) {
        $result = json_decode($response, true);
        if ($result && $result['status'] === 'success') {
            $categoryData = $result['data'];
        }
    }
}

$children = [];

// Thêm hidden field cho ID - QUAN TRỌNG: Luôn có ID field
$children[] = '<input type="hidden" name="id" value="' . ($categoryData['id'] ?? '') . '">';

foreach($productCategoryValidation as $field){
    // Lấy giá trị từ categoryData nếu có
    $value = $categoryData[$field['name']] ?? '';
    
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
    action: 'http://localhost/webbanhang/apis/ProductCategoryApi.php?route=save',
    method: 'POST'
);

// Thay đổi title và description dựa trên chế độ
$sheetTitle = $isEditMode ? 'CẬP NHẬT DANH MỤC SẢN PHẨM' : 'THÊM DANH MỤC SẢN PHẨM';
$sheetDescription = $isEditMode ? 'Cập nhật thông tin danh mục sản phẩm.' : 'Nhập thông tin danh mục sản phẩm để thêm mới.';

$sheet = new CustomSheet(
    children: [$alert, $form],
    class: 'user-sheet',
    title: $sheetTitle,
    description: $sheetDescription
);

echo $sheet->render([]);