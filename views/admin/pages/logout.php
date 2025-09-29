<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$_SESSION = [];
session_unset();
session_destroy();

if (isset($_COOKIE['token'])) {
    setcookie("token", "", time() - 3600, "/"); 
}

if (isset($_COOKIE['refresh_token'])) {
    setcookie("refresh_token", "", time() + 60*60*24*7, "/"); 
}

if (isset($_COOKIE['PHPSESSID'])) {
    setcookie("PHPSESSID", "", time() - 3600, "/");
}

header("Location: /WEBBANHANG/login");

exit;
