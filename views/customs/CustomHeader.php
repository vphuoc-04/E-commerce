<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$user = $_SESSION['user'] ?? null;
?>
<link rel="stylesheet" href="http://localhost/WEBBANHANG/views/customs/CustomHeader.css">

<div class="custom-header">
    <div class="header-right">
        <?php if ($user): ?>
            <div class="user-menu">
                <img src="<?= htmlspecialchars($user['img'] ?? 'http://localhost/WEBBANHANG/assets/default-avatar.png') ?>" 
                     alt="avatar" class="avatar" id="userAvatar">

                

                <div class="dropdown" id="dropdownMenu">
                    <ul>
                        <li><a href="#">Thông tin cá nhân</a></li>
                        <li><a href="#" id="logoutBtn">Đăng xuất</a></li>
                    </ul>
                </div>
            </div>
        <?php else: ?>
            <a href="index.php?page=login">Đăng nhập</a>
        <?php endif; ?>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const avatar = document.getElementById("userAvatar");
    const dropdown = document.getElementById("dropdownMenu");
    const logoutBtn = document.getElementById("logoutBtn");

    // Hàm xoá cookie (có path)
    function deleteCookie(name, path = "/") {
        document.cookie = name + "=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=" + path + ";";
    }

    if (avatar) {
        avatar.addEventListener("click", function (e) {
            e.stopPropagation();
            dropdown.classList.toggle("show");
        });
    }

    if (logoutBtn) {
    logoutBtn.addEventListener("click", function (e) {
        e.preventDefault();
        window.location.href = "views/admin/pages/logout.php"; 
    });
}


    // Ẩn dropdown khi click ngoài
    document.addEventListener("click", function (e) {
        if (!e.target.closest(".user-menu")) {
            dropdown.classList.remove("show");
        }
    });
});
</script>


