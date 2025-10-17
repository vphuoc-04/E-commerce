<?php

include __DIR__ . '/../../bases/BaseRender.php';

class ProductRender extends BaseRender {
    public function __construct() {
        $this->describe = [
            [
                "title" => "QUẢN LÝ THÔNG TIN SẢN PHẨM",
                "description" => "Đây là trang quản lý thông tin sản phẩm, với các chức năng..."
            ]
        ];

        $this->table = [
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
                        $imageUrl = "http://localhost/webbanhang/" . $item["image"];
                        return "<img src='{$imageUrl}' alt='' style='width:40px;height:40px;border-radius:8px;object-fit:cover;' />";
                    }
                    return "<div style='width:40px;height:40px;border-radius:8px;background:#eee;display:flex;align-items:center;justify-content:center;'></div>";
                }
            ],
            [
                "name" => "Tên sản phẩm",
                "render" => fn($item) => "<span>{$item["name"]}</span>"
            ],
            [
                "name" => "Loại sản phẩm",
                "render" => function($item) {
                    $categoryName = $item['category']['name'] ?? 'Không có';
                    return "<span>" . htmlspecialchars($categoryName) . "</span>";
                }
            ],
            [
                "name" => "Đơn giá",
                "className" => "text-right",
                "render" => fn($item) => "<span>" . number_format($item["price"], 0, ',', '.') . " đ</span>"
            ],
            [
                "name" => "Mô tả",
                "render" => fn($item) => "<span>" . (!empty($item["description"]) ? $item["description"] : "—") . "</span>"
            ]
        ];

        $this->buttonAction = [
            [
                "label" => "Thêm sản phẩm",
                "icon" => "<i class='fa fa-plus'></i>",
                "className" => "btn-add",
                "onClick" => "(function(){var s=document.querySelector('.custom-sheet'); if(s&&s.open) s.open();})()",
            ],
            [
                "label" => "Xuất báo cáo ra Exel",
                "icon" => "<i class='fa-solid fa-file-export'></i>",
                "className" => "btn-exel",
                "onClick" => "(function(){var s=document.querySelector('.custom-sheet'); if(s&&s.open) s.open();})()",
            ],
        ];
        $this->buttonTableActions = [
            [
                "icon" => "<i class='fa fa-edit'></i>",
                "className" => "btn btn-edit",
                "method" => "edit",
                // "onClick" => "(function(){var s=document.querySelector('.custom-sheet'); if(s&&s.open) s.open();})()",
                "onClick" => "editProduct(%id%)"
            ],
            [
                "icon" => "<i class='fa fa-trash'></i>",
                "className" => "btn btn-delete",
                "method" => "delete",
                "onClick" => "destroyProduct(%id%)"
            ]
        ];
    }
}