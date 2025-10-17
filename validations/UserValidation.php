<?php
// Cấu hình validations cho form Người dùng
$userValidation = [
    [
        'label' => 'Họ *',
        'name' => 'last_name',
        'type' => 'text',
        'rules' => [
            'required' => 'Bạn cần nhập họ',
            'minlength' => [ 'value' => 1, 'message' => 'Họ phải có ít nhất 1 ký tự' ],
            'maxlength' => [ 'value' => 100, 'message' => 'Họ không vượt quá 100 ký tự' ]
        ],
    ],
    [
        'label' => 'Tên đệm',
        'name' => 'middle_name',
        'type' => 'text',
        'rules' => [
            'maxlength' => [ 'value' => 100, 'message' => 'Tên đệm không vượt quá 100 ký tự' ]
        ],
    ],
    [
        'label' => 'Tên *',
        'name' => 'first_name',
        'type' => 'text',
        'rules' => [
            'required' => 'Bạn cần nhập tên',
            'minlength' => [ 'value' => 1, 'message' => 'Tên phải có ít nhất 1 ký tự' ],
            'maxlength' => [ 'value' => 100, 'message' => 'Tên không vượt quá 100 ký tự' ]
        ],
    ],
    [
        'label' => 'Giới tính *',
        'name' => 'gender',
        'type' => 'select',
        'options' => [ '1' => 'Nam', '2' => 'Nữ', '3' => 'Khác' ],
        'placeholder' => 'Chọn giới tính',
        'rules' => [
            'required' => 'Bạn cần chọn giới tính'
        ]
    ],
    [
        'label' => 'Ngày sinh',
        'name' => 'birth_date',
        'type' => 'date',
        'rules' => []
    ],
    [
        'label' => 'Số điện thoại *',
        'name' => 'phone',
        'type' => 'text',
        'rules' => [
            'required' => 'Bạn cần nhập số điện thoại',
            'pattern' => [ 'value' => '(0[0-9]{9}|84[0-9]{9})', 'message' => 'Số điện thoại không hợp lệ' ],
            'minlength' => [ 'value' => 10, 'message' => 'Số điện thoại phải có 10 ký tự' ],
            'maxlength' => [ 'value' => 11, 'message' => 'Số điện thoại không vượt quá 11 ký tự' ]
        ]
    ],
    [
        'label' => 'Email *',
        'name' => 'email',
        'type' => 'email',
        'rules' => [ 'required' => 'Bạn cần nhập email' ]
    ],
    [
        'label' => 'Mật khẩu *',
        'name' => 'password',
        'type' => 'password',
        'rules' => [
            'required' => 'Bạn cần nhập mật khẩu',
            'minlength' => [ 'value' => 6, 'message' => 'Mật khẩu tối thiểu 6 ký tự' ]
        ]
    ],
    [
        'label' => 'Nhóm người dùng *',
        'name' => 'catalogue_id',
        'type' => 'select',
        'options' => [], // sẽ điền động nếu có dữ liệu bên ngoài
        'placeholder' => 'Chọn nhóm người dùng',
        'rules' => [
            'required' => 'Bạn cần chọn nhóm người dùng'
        ]
    ],
];
