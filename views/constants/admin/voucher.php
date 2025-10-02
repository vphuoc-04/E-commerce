<?php 

 $describe = [
    [
        "title" => "QUẢN LÝ THÔNG TIN KHUYẾN MÃI",
        "description" => "Đây là trang quản lý thông tin khuyến mãi, với các chức năng..."
    ]
];

$table = [
    [
        "name" => "Mã Voucher",
        "render" => fn($item) => "<span>#{$item["code"]}</span>"
    ],
    [
        "name" => "Mô tả",
        "render" => fn($item) => "<span>{$item["description"]}</span>"
    ],
    [
        "name" => "Loại giảm giá",
        "className" => "text-center",
        "render" => fn($item) => "<span>{$item["discountType"]}</span>"
    ],
    [
        "name" => "Giá trị giảm",
        "className" => "text-right",
        "render" => function($item) {
            if ($item["discountType"] === "PERCENT") {
                return "<span>{$item["discountValue"]}%</span>";
            }
            return "<span>" . number_format($item["discountValue"], 0, ',', '.') . " đ</span>";
        }
    ],
    [
        "name" => "Ngày bắt đầu",
        "className" => "text-center",
        "render" => fn($item) => "<span>{$item["startDate"]}</span>"
    ],
    [
        "name" => "Ngày hết hạn",
        "className" => "text-center",
        "render" => fn($item) => "<span>{$item["endDate"]}</span>"
    ],
    [
        "name" => "Trạng thái",
        "className" => "text-center",
        "render" => function($item) {
            $statusColors = [
                "Active"   => "#22c55e", // xanh lá
                "Expired"  => "#ef4444", // đỏ
                "Disabled" => "#9ca3af"  // xám
            ];
            $color = $statusColors[$item["status"]] ?? "#6b7280";
            return "<span style='padding:4px 8px;border-radius:12px;background:{$color};color:#fff;font-size:12px;'>{$item["status"]}</span>";
        }
    ]
];
