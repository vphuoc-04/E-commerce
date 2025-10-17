<?php
function getCurrentPage() {
    // lấy phần path (không có query string)
    $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $path = trim($path, '/'); // bỏ 2 đầu dấu /

    // nếu path rỗng (ví dụ truy cập root hoặc /?page=...), fallback về ?page
    if ($path === '') {
        return $_GET['client'] ?? 'home';
    }
    // lấy segment cuối của path
    $segments = explode('/', $path);
    $last = end($segments);

    // nếu segment cuối là script (ví dụ index.php) thì dùng ?page
    $scriptBasename = basename($_SERVER['SCRIPT_NAME']); // thường là "index.php"
    if ($last === $scriptBasename) {
        return $_GET['client'] ?? 'home';
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
        "items" => [
            [
                "label" => "Trang chủ",
                "active" => ["home"],
                // "to" => createMenuLink("home")
                "to" => "home"
            ],
            [
                "label" => "Giới thiệu",
                "active" => ["about"],
                "to" => "about"
            ]
        ]
    ],
];