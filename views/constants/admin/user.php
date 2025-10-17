<?php

include __DIR__ . '/../../bases/BaseRender.php';

class UserRender extends BaseRender {
    public function __construct() {
        $this->describe = [
            [
                "title" => "QUẢN LÝ THÔNG TIN TẤT CẢ NGƯỜI DÙNG",
                "description" => "Đây là trang quản lý thông tin tất cả người dùng, với các chức năng lọc bên dưới."
            ]
        ];

        $this->table = [
            [
                "name" => "ID",
                "render" => function($item) {
                    $avatar = !empty($item["img"])
                        ? "<img src='{$item["img"]}' alt='avatar' style='width:32px;height:32px;border-radius:50%;'/>"
                        : "<div style='width:32px;height:32px;border-radius:50%;background:#ccc;'></div>";

                    return "<div style='display:flex;align-items:center;gap:8px;'>{$avatar}<span>{$item["id"]}</span></div>";
                }
            ],
            [
                "name" => "Họ",
                "render" => fn($item) => "<span>{$item["lastName"]}</span>"
            ],
            [
                "name" => "Tên đệm",
                "render" => fn($item) => "<span>{$item["middleName"]}</span>"
            ],
            [
                "name" => "Tên",
                "render" => fn($item) => "<span>{$item["firstName"]}</span>"
            ],
            [
                "name" => "Giới tính",
                "className" => "text-center",
                "render" => function($item) {
                    $genders = [1 => "Nam", 2 => "Nữ"];
                    return "<span>" . ($genders[$item["gender"]] ?? "Không rõ") . "</span>";
                }
            ],
            [
                "name" => "Ngày sinh",
                "className" => "text-center",
                "render" => fn($item) => "<span>" . ($item["birthDate"] ?? "Không rõ") . "</span>"
            ],
            [
                "name" => "Số điện thoại",
                "render" => fn($item) => "<span>{$item["phone"]}</span>"
            ],
            [
                "name" => "Email",
                "render" => fn($item) => "<span>{$item["email"]}</span>"
            ],
            [
                "name" => "Nhóm thành viên",
                "render" => function($item) {
                    $catalougeName = $item['catalogue']['name'] ?? 'Không có';
                    return "<span>" . htmlspecialchars($catalougeName) . "</span>";
                }
            ]
        ];

        $this->buttonAction = [
            [
                "label" => "Thêm người dùng",
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
                "onClick" => "editUser(%id%)"
            ],
            [
                "icon" => "<i class='fa fa-trash'></i>",
                "className" => "btn btn-delete",
                "method" => "delete",
                "onClick" => "destroyUser(%id%)"
            ],
            [
                "icon" => "<i class='fa fa-search'></i>",
                "className" => "btn btn-view",
                "method" => "view",
            ],
        ];
    }
}