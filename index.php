<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include 'configs/router.php';

if ($page === 'login' || $page === 'register') {
    require_once __DIR__ . '/middlewares/guest.php';
    requireGuest();
    include $content;

} elseif ($client === 'login' || $client === 'register' || $client === 'verify-otp' || $client === 'resend-otp') {
    require_once __DIR__ . '/middlewares/guest.php';
    requireGuest();
    include $content;

} elseif ($client === 'logout') {
    include $content;

} elseif (!empty($client) || $page === null) {
    include 'views/client/components/layout.php';

} else {
    require_once __DIR__ . '/middlewares/auth.php';
    requireAuth();
    include 'views/admin/components/layout.php';
}
