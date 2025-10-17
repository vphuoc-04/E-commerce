<?php

// Import custom components
require_once __DIR__ . '/../../customs/CustomInput.php';
require_once __DIR__ . '/../../customs/CustomDropBoxMenu.php';
require_once __DIR__ . '/../../customs/CustomButton.php';
require_once __DIR__ . '/../../customs/CustomForm.php';
require_once __DIR__ . '/../../customs/CustomAlert.php';
require_once __DIR__ . '/../../customs/CustomSheet.php';

// Validations
require_once __DIR__ . '/../../../validations/UserValidation.php';

// Kiểm tra xem có đang ở chế độ edit không từ URL
$isEditMode = isset($_GET['edit']) && !empty($_GET['edit']);
$userId = $isEditMode ? $_GET['edit'] : null;
$userData = null;

// Nếu là edit mode, lấy dữ liệu người dùng từ API
if ($isEditMode && $userId) {
    $apiUrl = "http://localhost/webbanhang/apis/UserApi.php?route=show&id=" . $userId;
    $context = stream_context_create([
        'http' => ['ignore_errors' => true]
    ]);
    $response = file_get_contents($apiUrl, false, $context);
    
    if ($response !== false) {
        $result = json_decode($response, true);
        if ($result && $result['status'] === 'success') {
            $userData = $result['data'];
        }
    }
}

// Ánh xạ options động nếu có
$cataloguesOptions = $cataloguesOptions ?? [];
$validationWithOptions = array_map(function($field) use ($cataloguesOptions) {
    if ($field['type'] === 'select' && $field['name'] === 'catalogue_id') {
        if (isset($cataloguesOptions) && is_array($cataloguesOptions)) {
            $field['options'] = $cataloguesOptions; // [id => name]
        }
    }
    return $field;
}, $userValidation);

$children = [];

// Thêm hidden field cho ID - QUAN TRỌNG: Luôn có ID field
$children[] = '<input type="hidden" name="id" value="' . ($userData['id'] ?? '') . '">';

foreach($validationWithOptions as $field) {
    // Lấy giá trị từ userData nếu có
    $value = $userData[$field['name']] ?? '';
    
    $children[] = '<div class="field-group">';
    $children[] = '<span class="field-label">' . preg_replace('/\s*\*$/', '', $field['label']) . '</span>';
    
    if (in_array($field['type'], ['text','email','password','date'])) {
        // Không fill lại password trong chế độ edit
        if ($field['name'] === 'password' && $isEditMode) {
            $value = '';
        }
        
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
    elseif ($field['type'] === 'select') {
        $children[] = new CustomDropBoxMenu(
            class: 'user-drop-box-menu',
            name: $field['name'],
            options: $field['options'] ?? [],
            selected: $value,
            required: isset($field['rules']['required']),
            placeholder: $field['placeholder'] ?? 'Chọn...'
        );
    }
    
    $children[] = '</div>';
}

// Thay đổi text button dựa trên chế độ
$buttonText = $isEditMode ? "Cập nhật" : "Thêm mới";
$children[] = new CustomButton($buttonText, false, true, "user-button-confirm");

$form = new CustomForm(
    children: $children,
    class: 'user-form',
    action: 'http://localhost/webbanhang/apis/UserApi.php?route=save',
    method: 'POST'
);

$alert = new CustomAlert('custom-alert', '', 'top-right');

// Thay đổi title và description dựa trên chế độ
$sheetTitle = $isEditMode ? 'CẬP NHẬT NGƯỜI DÙNG' : 'THÊM MỚI NGƯỜI DÙNG';
$sheetDescription = $isEditMode ? 'Cập nhật thông tin người dùng.' : 'Nhập thông tin người dùng để thêm mới vào hệ thống.';

$sheet = new CustomSheet(
    children: [$alert, $form],
    class: 'user-sheet',
    title: $sheetTitle,
    description: $sheetDescription
);

echo $sheet->render([]);