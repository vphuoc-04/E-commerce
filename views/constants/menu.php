<?php
function getCurrentPage() {
    $currentPath = $_SERVER['REQUEST_URI'];
    $pathParts = explode('?', $currentPath);
    $pageName = basename($pathParts[0]);
    
    if ($pageName == 'index.php' || empty($pageName)) {
        return $_GET['page'] ?? 'dashboard';
    }
    
    return $pageName;
}

function createMenuLink($pageName, $pageNumber = 1) {
    return "$pageName?page=$pageNumber";
}

$currentPage = getCurrentPage();

$menu = [
    [
        "label" => "TRANG CHỦ",
        "items" => [
            [
                "icon" => "fa-solid fa-house",
                "label" => "Tổng quan",
                "active" => ["dashboard"],
                "to" => createMenuLink("dashboard"),
                "links" => [],
                "isActive" => in_array($currentPage, ["dashboard"])
            ],
        ],
    ],
    [
        "label" => "CHỨC NĂNG",
        "items" => [
            [
                "icon" => "fa-solid fa-users",
                "label" => "Người dùng",
                "active" => ["users", "employees", "customers"], 
                "to" => "#", 
                "links" => [
                    "items" => [
                        [
                            "icon" => "fa-solid fa-users",
                            "label" => "Tất cả người dùng",
                            "active" => ["users"],
                            "to" => createMenuLink("users"),
                            "isActive" => in_array($currentPage, ["users"])
                        ],
                        [
                            "icon" => "fa-solid fa-user-tie",
                            "label" => "Nhân viên",
                            "active" => ["employees"],
                            "to" => createMenuLink("employees"),
                            "isActive" => in_array($currentPage, ["employees"])
                        ],
                        [
                            "icon" => "fa-solid fa-users",
                            "label" => "Khách hàng",
                            "active" => ["customers"],
                            "to" => createMenuLink("customers"),
                            "isActive" => in_array($currentPage, ["customers"])
                        ]
                    ]
                ],
                "isActive" => in_array($currentPage, ["users", "employees", "customers"])
            ],
            [
                "icon" => "fa-solid fa-box",
                "label" => "Quản lý nhóm người dùng",
                "active" => ["catalogues"],
                "to" => createMenuLink("catalogues"),
                "links" => [],
                "isActive" => in_array($currentPage, ["catalogues"])
            ],
            [
                "icon" => "fa-solid fa-box",
                "label" => "Quản lý sản phẩm",
                "active" => ["products"],
                "to" => createMenuLink("products"),
                "links" => [],
                "isActive" => in_array($currentPage, ["products"])
            ],
            [
                "icon" => "fa-solid fa-file-invoice",
                "label" => "Đơn hàng",
                "active" => ["orders"],
                "to" => createMenuLink("orders"),
                "links" => [],
                "isActive" => in_array($currentPage, ["orders"])
            ],
            [
                "icon" => "fa-solid fa-tags",
                "label" => "Khuyến mãi",
                "active" => ["vouchers"],
                "to" => createMenuLink("vouchers"),
                "links" => [],
                "isActive" => in_array($currentPage, ["vouchers"])
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
                "to" => createMenuLink("reports"),
                "links" => [],
                "isActive" => in_array($currentPage, ["reports"])
            ]
        ]
    ]
];
?>