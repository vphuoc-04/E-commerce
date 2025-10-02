<?php
$describe = [
    [
        "title" => "QUẢN LÝ THÔNG TIN SẢN PHẨM",
        "description" => "Đây là trang quản lý thông tin sản phẩm, với các chức năng..."
    ]
];

$table = [
    [
        "name" => "ID",
        "render" => function($item) {
            return "<span>{$item["id"]}</span>";
        }
    ],
    [
        "name" => "Ảnh",
        "render" => function($item) {
            if (!empty($item["image"])) {
                return "<img src='{$item["image"]}' alt='product' style='width:40px;height:40px;border-radius:8px;object-fit:cover;' />";
            }
            return "<div style='width:40px;height:40px;border-radius:8px;background:#eee;display:flex;align-items:center;justify-content:center;'>N/A</div>";
        }
    ],
    [
        "name" => "Tên sản phẩm",
        "render" => fn($item) => "<span>{$item["name"]}</span>"
    ],
    [
        "name" => "Loại sản phẩm",
        "render" => fn($item) => "<span>{$item["categoryName"]}</span>"
    ],
    [
        "name" => "Đơn giá",
        "className" => "text-right",
        "render" => fn($item) => "<span>" . number_format($item["price"], 0, ',', '.') . " đ</span>"
    ],
    [
        "name" => "Tồn kho",
        "className" => "text-center",
        "render" => fn($item) => "<span>{$item["stock"]}</span>"
    ],
    [
        "name" => "Mô tả",
        "render" => fn($item) => "<span>" . (!empty($item["description"]) ? $item["description"] : "—") . "</span>"
    ]
];
