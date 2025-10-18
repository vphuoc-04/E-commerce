<?php
$home = [
    [
        "name" => "Ảnh",
        "render" => function($item) {
            $id = htmlspecialchars($item['id'] ?? '');
            $image = htmlspecialchars($item['image'] ?? '');
            $name = htmlspecialchars($item['name'] ?? 'Sản phẩm');

            // Khi click vào ảnh -> sang trang single product
            return "
                <a href='?client=single-product&id={$id}&name={$name}' class='product-link'>
                    <img src='{$image}' alt='{$name}' style='width:60px;height:60px;object-fit:cover;border-radius:8px;'>
                </a>
            ";
        }
    ],
    [
        "name" => "Tên sản phẩm",
        "render" => function($item) {
            $id = htmlspecialchars($item['id'] ?? '');
            $name = htmlspecialchars($item['name'] ?? 'Không có tên');

            // Khi click vào tên -> sang trang single product
            return "
                <a href='?client=single-product&id={$id}&name={$name}' class='product-name-link'>
                    {$name}
                </a>
            ";
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
