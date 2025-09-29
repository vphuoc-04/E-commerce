<?php
function requireGuest() {
    if (isset($_COOKIE['token'])) {
        header("Location: dashboard");
        exit;
    }
}
