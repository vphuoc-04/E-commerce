<?php
$describe = [
    [
        "title" => "QUẢN LÝ THÔNG TIN KHÁCH HÀNG",
        "description" => "Đây là trang quản lý thông tin khách hàng, với các chức năng..."
    ]
];

$table = [
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
        "render" => fn($item) => "<span>{$item["userCatalogueName"]}</span>"
    ]
];
