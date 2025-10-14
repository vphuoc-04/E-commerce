<?php
$isLoggedIn = isset($_SESSION['user']);
$user = $_SESSION['user'] ?? null;
$username = $user['firstName'] ?? $user['email'] ?? 'Khách';
?>

<header class="site-header">
    <div class="header-inner">
        <div class="header-left">
            <a href="home" class="logo">
                <i class="fas fa-shopping-cart"></i>
                <span>My web</span>
            </a>
        </div>

        <nav class="header-nav">
            <ul>
                <li><a href="home" class="nav-link">
                    <span>Trang chủ</span>
                </a></li>
                <li><a href="products" class="nav-link">
                    <span>Sản phẩm</span>
                </a></li>
                <li><a href="about" class="nav-link">
                    <span>Giới thiệu</span>
                </a></li>
                <li><a href="cart" class="nav-link cart-link">
                    <span>Giỏ hàng</span>
                    <span class="cart-count">0</span>
                </a></li>
            </ul>
        </nav>

        <?php /*
        <div class="header-right">
            <?php if ($isLoggedIn): ?>
                <div class="user-menu">
                    <div class="user-info">
                        <div class="user-avatar">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="user-details">
                            <span class="welcome">Xin chào,</span>
                            <span class="username"><?= htmlspecialchars($username) ?></span>
                        </div>
                    </div>
                    <div class="user-dropdown">
                        <a href="profile" class="dropdown-item">
                            Thông tin cá nhân
                        </a>
                        <a href="orders" class="dropdown-item">
                            <i class="fas fa-shopping-bag"></i>
                            Đơn hàng
                        </a>
                        <a href="settings" class="dropdown-item">
                            Cài đặt
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="logout" class="dropdown-item logout">
                            Đăng xuất
                        </a>
                    </div>
                </div>
            <?php else: ?>
                <div class="auth-buttons">
                    <a href="login" class="btn btn-login">
                        <i class="fas fa-sign-in-alt"></i>
                        Đăng nhập
                    </a>
                    <a href="register" class="btn btn-register">
                        <i class="fas fa-user-plus"></i>
                        Đăng ký
                    </a>
                </div>
            <?php endif; ?>
        </div>
        */ ?>

        <button class="mobile-menu-toggle" onclick="toggleMobileMenu()">
            <i class="fas fa-bars"></i>
        </button>
    </div>

    <!-- Mobile Navigation -->
    <div class="mobile-nav" id="mobileNav">
        <div class="mobile-nav-content">
            <div class="mobile-nav-header">
                <div class="logo">
                    <i class="fas fa-shopping-cart"></i>
                    <span>Web Bán Hàng</span>
                </div>
                <button class="mobile-close" onclick="toggleMobileMenu()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <nav class="mobile-nav-menu">
                <a href="home" class="mobile-nav-link">
                    <i class="fas fa-home"></i>
                    <span>Trang chủ</span>
                </a>
                <a href="products" class="mobile-nav-link">
                    <i class="fas fa-box"></i>
                    <span>Sản phẩm</span>
                </a>
                <a href="about" class="mobile-nav-link">
                    <i class="fas fa-info-circle"></i>
                    <span>Giới thiệu</span>
                </a>
                <a href="cart" class="mobile-nav-link">
                    <i class="fas fa-shopping-cart"></i>
                    <span>Giỏ hàng</span>
                </a>
            </nav>

            <div class="mobile-auth">
                <?php if ($isLoggedIn): ?>
                    <div class="mobile-user-info">
                        <div class="user-avatar">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="user-details">
                            <span class="welcome">Xin chào,</span>
                            <span class="username"><?= htmlspecialchars($username) ?></span>
                        </div>
                    </div>
                    <div class="mobile-user-menu">
                        <a href="profile" class="mobile-user-link">
                            <i class="fas fa-user"></i>
                            Thông tin cá nhân
                        </a>
                        <a href="orders" class="mobile-user-link">
                            <i class="fas fa-shopping-bag"></i>
                            Đơn hàng
                        </a>
                        <a href="settings" class="mobile-user-link">
                            <i class="fas fa-cog"></i>
                            Cài đặt
                        </a>
                        <a href="logout" class="mobile-user-link logout">
                            <i class="fas fa-sign-out-alt"></i>
                            Đăng xuất
                        </a>
                    </div>
                <?php else: ?>
                    <a href="login" class="btn btn-login mobile-btn">
                        <i class="fas fa-sign-in-alt"></i>
                        Đăng nhập
                    </a>
                    <a href="register" class="btn btn-register mobile-btn">
                        <i class="fas fa-user-plus"></i>
                        Đăng ký
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</header>

<script>
function toggleMobileMenu() {
    const mobileNav = document.getElementById('mobileNav');
    const body = document.body;
    
    if (mobileNav.classList.contains('active')) {
        mobileNav.classList.remove('active');
        body.classList.remove('mobile-menu-open');
    } else {
        mobileNav.classList.add('active');
        body.classList.add('mobile-menu-open');
    }
}

// Close mobile menu when clicking outside
document.addEventListener('click', function(event) {
    const mobileNav = document.getElementById('mobileNav');
    const mobileToggle = document.querySelector('.mobile-menu-toggle');
    
    if (!mobileNav.contains(event.target) && !mobileToggle.contains(event.target)) {
        mobileNav.classList.remove('active');
        document.body.classList.remove('mobile-menu-open');
    }
});

// User dropdown functionality
document.addEventListener('DOMContentLoaded', function() {
    const userMenu = document.querySelector('.user-menu');
    if (userMenu) {
        userMenu.addEventListener('click', function(e) {
            e.stopPropagation();
            this.classList.toggle('active');
        });
        
        // Close dropdown when clicking outside
        document.addEventListener('click', function() {
            userMenu.classList.remove('active');
        });
    }
});
</script>
