<?php
function getCurrentPage() {
    // lấy phần path (không có query string)
    $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $path = trim($path, '/'); // bỏ 2 đầu dấu /

    // nếu path rỗng (ví dụ truy cập root hoặc /?page=...), fallback về ?page
    if ($path === '') {
        return $_GET['page'] ?? 'dashboard';
    }
    // lấy segment cuối của path
    $segments = explode('/', $path);
    $last = end($segments);

    // nếu segment cuối là script (ví dụ index.php) thì dùng ?page
    $scriptBasename = basename($_SERVER['SCRIPT_NAME']); // thường là "index.php"
    if ($last === $scriptBasename) {
        return $_GET['page'] ?? 'dashboard';
    }
    // loại .php nếu có (ví dụ users.php -> users)
    $last = preg_replace('/\.php$/', '', $last);

    return $last;
}

function createMenuLink($pageName, $pageNumber = 1) {
    return "$pageName?page=$pageNumber";
}

$menu = [
    [
        "label" => "TRANG CHỦ",
        "items" => [
            [
                "icon" => "fa-solid fa-house",
                "label" => "Tổng quan",
                "active" => ["dashboard"],
                "to" => "dashboard",
                "links" => [],
            ],
        ],
    ],
    [
        "label" => "CHỨC NĂNG",
        "items" => [
            [
                "icon" => "fa-solid fa-users",
                "label" => "Người dùng",
                "active" => ["users", "employees", "customers", "catalogues"], 
                "to" => "#", 
                "links" => [
                    "items" => [
                        [
                            "icon" => "fa-solid fa-users",
                            "label" => "Tất cả người dùng",
                            "active" => ["users"],
                            "to" => createMenuLink("users"),
                        ],
                        [
                            "icon" => "fa-solid fa-user-tie",
                            "label" => "Nhân viên",
                            "active" => ["employees"],
                            "to" => createMenuLink("employees"),
                        ],
                        [
                            "icon" => "fa-solid fa-users",
                            "label" => "Khách hàng",
                            "active" => ["customers"],
                            "to" => createMenuLink("customers"),
                        ],
                        [
                            "icon" => "fa-solid fa-box",
                            "label" => "Quản lý nhóm người dùng",
                            "active" => ["catalogues"],
                            "to" => createMenuLink("catalogues"),
                        ],
                    ]
                ],
            ],
            [
                "icon" => "fa-solid fa-box",
                "label" => "Sản phẩm",
                "active" => ["products", "categories", "vouchers"], 
                "to" => "#", 
                "links" => [
                    "items" => [
                        [
                            "icon" => "fa-solid fa-box",
                            "label" => "Quản lý sản phẩm",
                            "active" => ["products"],
                            "to" => createMenuLink("products"),
                            "links" => [],
                        ],
                        [
                            "icon" => "fa-solid fa-box",
                            "label" => "Quản lý nhóm sản phẩm",
                            "active" => ["categories"],
                            "to" => createMenuLink("categories"),
                            "links" => [],
                        ],
                        [
                            "icon" => "fa-solid fa-tags",
                            "label" => "Khuyến mãi",
                            "active" => ["vouchers"],
                            "to" => createMenuLink("vouchers"),
                            "links" => [],
                        ]
                    ]
                ]
            ],
            [
                "icon" => "fa-solid fa-file-invoice",
                "label" => "Đơn hàng",
                "active" => ["orders"],
                "to" => createMenuLink("orders"),
                "links" => [],
            ],
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
            ]
        ]
    ]
];
?>