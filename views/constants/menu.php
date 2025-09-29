<?php
$menu = [
    [
        "label" => "TRANG CHỦ",
        "items" => [
            [
                "icon" => "fa-solid fa-house",
                "label" => "Tổng quan",
                "active" => ["dashboard"],
                "to" => "dashboard",
                "links" => []
            ],
        ],
    ],
    [
        "label" => "CHỨC NĂNG",
        "items" => [
            [
                "icon" => "fa-solid fa-users",
                "label" => "Người dùng",
                "active" => ["employees", "customers"], 
                "to" => "#", 
                "links" => [
                    "items" => [
                        [
                            "icon" => "fa-solid fa-user-tie",
                            "label" => "Nhân viên",
                            "active" => ["employees"],
                            "to" => "employees",
                        ],
                        [
                            "icon" => "fa-solid fa-users",
                            "label" => "Khách hàng",
                            "active" => ["customers"],
                            "to" => "customers",
                        ]
                    ]
                ]
            ],
                        [
                "icon" => "fa-solid fa-box",
                "label" => "Quản lý nhóm người dùng",
                "active" => ["catalogues"],
                "to" => "catalogues",
                "links" => []
            ],
            [
                "icon" => "fa-solid fa-box",
                "label" => "Quản lý sản phẩm",
                "active" => ["products"],
                "to" => "products",
                "links" => []
            ],
            [
                "icon" => "fa-solid fa-file-invoice",
                "label" => "Đơn hàng",
                "active" => ["orders"],
                "to" => "orders",
                "links" => []
            ],
            [
                "icon" => "fa-solid fa-tags",
                "label" => "Khuyến mãi",
                "active" => ["vouchers"],
                "to" => "vouchers",
                "links" => []
            ]
        ]
    ],
    [
        "label" => "BÁO CÁO",
        "items" => [
            [
                "icon" => "fa-solid fa-chart-bar",
                "label" => "Thống kê",
                "active" => ["reports"],
                "to" => "reports",
                "links" => []
            ]
        ]
    ]
];