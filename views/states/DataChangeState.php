<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$dataChanged = $_SESSION["data_changed"] ?? false;

if ($dataChanged) {
    include 'CustomLoading.php';
    $_SESSION["data_changed"] = false;
}
