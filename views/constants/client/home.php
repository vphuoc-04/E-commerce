<?php
$home = [
    [
        "name" => "Ảnh",
        "render" => function($item) {
            $image = htmlspecialchars($item['image'] ?? '');
            return "<img src='{$image}' alt=''>";
        }
    ],
    [
        "name" => "Tên sản phẩm",
        "render" => function($item) {
            return "<span>" . htmlspecialchars($item['name'] ?? 'Không có tên') . "</span>";
        }
    ],
    [
        "name" => "Giá",
        "render" => function($item) {
            return "<span>" . number_format($item['price'] ?? 0, 0, ',', '.') . " ₫</span>";
        }
    ],
    [
        "name" => "Tồn kho",
        "render" => function($item) {
            return "<span>" . htmlspecialchars($item['stock'] ?? 0) . "</span>";
        }
    ],
    [
        "name" => "Danh mục",
        "render" => function($item) {
            $categoryName = $item['category']['name'] ?? 'Không có';
            return "<span>" . htmlspecialchars($categoryName) . "</span>";
        }
    ]
];
?>
