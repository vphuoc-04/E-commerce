<?php 
 $describe = [
    [
        "title" => "QUẢN LÝ THÔNG TIN ĐƠN HÀNG",
        "description" => "Đây là trang quản lý thông tin đơn hàng, với các chức năng..."
    ]
];

$table = [
    [
        "name" => "Mã đơn",
        "render" => fn($item) => "<span>#{$item["id"]}</span>"
    ],
    [
        "name" => "Khách hàng",
        "render" => fn($item) => "<span>{$item["customerName"]}</span>"
    ],
    [
        "name" => "Ngày đặt",
        "className" => "text-center",
        "render" => fn($item) => "<span>{$item["orderDate"]}</span>"
    ],
    [
        "name" => "Tổng tiền",
        "className" => "text-right",
        "render" => fn($item) => "<span>" . number_format($item["total"], 0, ',', '.') . " đ</span>"
    ],
    [
        "name" => "Trạng thái",
        "className" => "text-center",
        "render" => function($item) {
            $statusColors = [
                "Chờ xác nhận" => "#facc15", // vàng
                "Xác nhận đơn hàng" => "#3b82f6", // xanh dương
                "Đang giao" => "#06b6d4", // cyan
                "Đã giao" => "#8b5cf6", // tím
                "Thành công" => "#22c55e", // xanh lá
                "Hủy" => "#ef4444" // đỏ
            ];
            $color = $statusColors[$item["status"]] ?? "#9ca3af"; // mặc định xám
            return "<span style='padding:4px 8px;border-radius:12px;background:{$color};color:#fff;font-size:12px;'>{$item["status"]}</span>";
        }
    ]
];
