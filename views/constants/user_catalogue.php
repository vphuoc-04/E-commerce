<?php
$describe = [
    [
        "title" => "QUẢN LÝ THÔNG TIN NHÓM NGƯỜI DÙNG",
        "description" => "Đây là trang quản lý thông tin nhóm người dùng, với các chức năng lọc bên dưới."
    ]
];

$table = [
    [
        "name" => "ID",
        "render" => fn($item) => "<span>{$item["id"]}</span>"
    ],
    [
        "name" => "Tên",
        "render" => fn($item) => "<span>{$item["name"]}</span>"
    ],
    [
        "name" => "Mô tả",
        "render" => fn($item) => "<span>{$item["description"]}</span>"
    ],
    // [
    //     "name" => "Tên",
    //     "render" => fn($item) => "<span>{$item["firstName"]}</span>"
    // ],
    // [
    //     "name" => "Giới tính",
    //     "className" => "text-center",
    //     "render" => function($item) {
    //         $genders = [1 => "Nam", 2 => "Nữ"];
    //         return "<span>" . ($genders[$item["gender"]] ?? "Không rõ") . "</span>";
    //     }
    // ],
    // [
    //     "name" => "Ngày sinh",
    //     "className" => "text-center",
    //     "render" => fn($item) => "<span>" . ($item["birthDate"] ?? "Không rõ") . "</span>"
    // ],
    // [
    //     "name" => "Số điện thoại",
    //     "render" => fn($item) => "<span>{$item["phone"]}</span>"
    // ],
    // [
    //     "name" => "Email",
    //     "render" => fn($item) => "<span>{$item["email"]}</span>"
    // ],
    // [
    //     "name" => "Nhóm thành viên",
    //     "render" => fn($item) => "<span>{$item["userCatalogueName"]}</span>"
    // ]
];


$buttonActions = [
    [
        "path" => "/users/update",
        "icon" => "<i class='fa fa-edit'></i>",
        "className" => "btn btn-edit",
        "method" => "update",
    ],
    [
        "path" => "/users/delete",
        "icon" => "<i class='fa fa-trash'></i>",
        "className" => "btn btn-delete",
        "method" => "delete",
    ],
    [
        "path" => "/users/view",
        "icon" => "<i class='fa fa-search'></i>",
        "className" => "btn btn-view",
        "method" => "view",
    ],
];
