<?php

include __DIR__ . '/../../bases/BaseRender.php';

class UserCatalogueRender extends BaseRender {
    public function __construct() {
        $this->describe = [
            [
                "title" => "QUẢN LÝ THÔNG TIN NHÓM NGƯỜI DÙNG",
                "description" => "Đây là trang quản lý thông tin nhóm người dùng, với các chức năng lọc bên dưới."
            ]
        ];

        $this->table = [
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

        $this->buttonAction = [
            [
                "label" => "Thêm nhóm người dùng",
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
                "method" => "update",
                "onClick" => "editCatalogue(%id%)"
            ],
            [
                "icon" => "<i class='fa fa-trash'></i>",
                "className" => "btn btn-delete",
                "method" => "delete",
                "onClick" => "destroy(%id%)"
            ],
            [
                "icon" => "<i class='fa fa-search'></i>",
                "className" => "btn btn-view",
                "method" => "view",
            ],
        ];
    }
}