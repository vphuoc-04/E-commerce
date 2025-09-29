<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include 'configs/router.php';

if ($page === 'login') {
    require_once __DIR__ . '/middlewares/guest.php';
    requireGuest();
    include $content;
} else {
    require_once __DIR__ . '/middlewares/auth.php';
    requireAuth();
    include 'views/admin/components/layout.php';
}
