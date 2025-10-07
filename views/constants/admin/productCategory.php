<?php
$describe = [
    [
        "title" => "QUẢN LÝ THÔNG TIN NHÓM SẢN PHẨM",
        "description" => "Đây là trang quản lý thông tin nhóm sản phẩm, với các chức năng lọc bên dưới."
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
