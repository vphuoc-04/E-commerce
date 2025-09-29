<?php 
$describe = [
    [
        "title" => "QUẢN LÝ THÔNG TIN NHÂN VIÊN",
        "description" => "Đây là trang quản lý thông tin nhân viên, với các chức năng..."
    ]
];

$table = [
    [
        "name" => "Mã NV",
        "render" => fn($item) => "<span>#{$item["id"]}</span>"
    ],
    [
        "name" => "Họ tên",
        "render" => fn($item) => "<span>{$item["name"]}</span>"
    ],
    [
        "name" => "Email",
        "render" => fn($item) => "<span>{$item["email"]}</span>"
    ],
    [
        "name" => "Số điện thoại",
        "render" => fn($item) => "<span>{$item["phone"]}</span>"
    ],
    [
        "name" => "Phòng ban",
        "className" => "text-center",
        "render" => fn($item) => "<span>{$item["department"]}</span>"
    ],
    [
        "name" => "Lương",
        "className" => "text-right",
        "render" => fn($item) => "<span>" . number_format($item["salary"], 0, ',', '.') . " đ</span>"
    ],
    [
        "name" => "Trạng thái",
        "className" => "text-center",
        "render" => function($item) {
            $statusColors = [
                "Đang làm" => "#22c55e",     // xanh lá
                "Nghỉ phép" => "#facc15",   // vàng
                "Nghỉ việc" => "#ef4444",   // đỏ
                "Tạm hoãn" => "#3b82f6"     // xanh dương
            ];
            $color = $statusColors[$item["status"]] ?? "#9ca3af"; // mặc định xám
            return "<span style='padding:4px 8px;border-radius:12px;background:{$color};color:#fff;font-size:12px;'>{$item["status"]}</span>";
        }
    ]
];
