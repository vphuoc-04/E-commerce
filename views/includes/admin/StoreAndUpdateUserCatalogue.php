<?php

require_once __DIR__ . '/../../customs/CustomInput.php';
require_once __DIR__ . '/../../customs/CustomButton.php';
require_once __DIR__ . '/../../customs/CustomForm.php';
require_once __DIR__ . '/../../customs/CustomSheet.php';
require_once __DIR__ . '/../../customs/CustomAlert.php';

require_once __DIR__ . '/../../../validations/UserCatalogueValidation.php';

// Kiểm tra xem có đang ở chế độ edit không từ URL
$isEditMode = isset($_GET['edit']) && !empty($_GET['edit']);
$catalogueId = $isEditMode ? $_GET['edit'] : null;
$catalogueData = null;

// Nếu là edit mode, lấy dữ liệu nhóm người dùng từ API
if ($isEditMode && $catalogueId) {
    $apiUrl = "http://localhost/webbanhang/apis/UserCatalogueApi.php?route=show&id=" . $catalogueId;
    $context = stream_context_create([
        'http' => ['ignore_errors' => true]
    ]);
    $response = file_get_contents($apiUrl, false, $context);
    
    if ($response !== false) {
        $result = json_decode($response, true);
        if ($result && $result['status'] === 'success') {
            $catalogueData = $result['data'];
        }
    }
}

$children = [];

// Thêm hidden field cho ID - QUAN TRỌNG: Luôn có ID field
$children[] = '<input type="hidden" name="id" value="' . ($catalogueData['id'] ?? '') . '">';

foreach($userCatalogueValidation as $field){
    // Lấy giá trị từ catalogueData nếu có
    $value = $catalogueData[$field['name']] ?? '';
    
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
    action: 'http://localhost/webbanhang/apis/UserCatalogueApi.php?route=save',
    method: 'POST'
);

// Thay đổi title và description dựa trên chế độ
$sheetTitle = $isEditMode ? 'CẬP NHẬT NHÓM NGƯỜI DÙNG' : 'THÊM NHÓM NGƯỜI DÙNG';
$sheetDescription = $isEditMode ? 'Cập nhật thông tin nhóm người dùng.' : 'Nhập thông tin nhóm người dùng để thêm mới.';

$sheet = new CustomSheet(
    children: [$alert, $form],
    class: 'user-sheet',
    title: $sheetTitle,
    description: $sheetDescription
);

echo $sheet->render([]);