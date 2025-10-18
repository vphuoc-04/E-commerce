<?php
include_once 'views/customs/CustomButton.php';
include_once 'views/customs/CustomInput.php';

$searchInput = new CustomInput(
    type: 'text',
    class: 'search-input',
    placeholder: 'Tìm kiếm...',
    name: 'search',
    required: false
);

// $searchButton = new CustomButton(
//     text: '<i class="fas fa-search"></i>',
//     loading: false,
//     disabled: false,
//     class: 'search-btn'
// );

$isLoggedIn = isset($_SESSION['user']);
$user = $_SESSION['user'] ?? null;
$username = $user['firstName'] ?? $user['email'] ?? 'Khách';

// GỌI API để lấy danh mục sản phẩm
$apiUrl = "http://localhost/WEBBANHANG/apis/ProductCategoryApi.php?route=index";

$response = file_get_contents($apiUrl);
$data = json_decode($response, true);

// Kiểm tra response hợp lệ
if (
    isset($data['status']) &&
    $data['status'] === 'success' &&
    isset($data['data']['categories'])
) {
    $categories = $data['data']['categories'];
    $pagination = $data['data']['pagination'] ?? null;
} else {
    $categories = [];
    $pagination = null;
}
?>

<header class="site-header">
    <div class="header-inner">
        <div class="header-top">
            <div class="content">
                <div class="slogan">
                    Mỹ Phẩm Đẹp - Web mỹ phẩm chính hãng
                </div>
                <div class="info">
                    <i class="fa-regular fa-envelope"></i><span>myphamdepgmail.com</span>
                    <span>|</span>
                    <i class="fa-solid fa-phone"></i><span>012.345.678</span>
                </div>
            </div>
        </div>

        <div class="header-main">
            <div class="content">
                <a href="home" class="logo">
                    <!-- <i class="fas fa-shopping-cart"></i> -->
                    <span>Mỹ Phẩm Đẹp</span>
                </a>
                <div class="header-search">
                    <div class="search-box">
                        <!-- <input type="text" class="search-input" placeholder="Tìm kiếm sản phẩm..."> -->
                        <?= $searchInput->render() ?>
                        <button class="search-btn"><i class="fas fa-search"></i></button>

                        <div class="search-dropdown">

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <nav class="header-nav">
            <div class="content">
                <?php 
                    include 'views/constants/client/menu.php'; 
                    $currentPage = getCurrentPage();
                ?>
                <ul>
                    <?php foreach ($menu as $section): ?>
                        <?php foreach ($section['items'] as $item): ?>
                            <?php 
                                $isActive = in_array($currentPage, $item['active']) ? 'active' : '';
                            ?>
                            <li>
                                <a href="<?= $item['to'] ?>" class="nav-link <?= $isActive ?>">
                                    <?= $item['label'] ?>
                                    <?php if (isset($item['badge']) && $item['badge'] > 0): ?>
                                        <span class="cart-count"><?= $item['badge'] ?></span>
                                    <?php endif; ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    <?php endforeach; ?>

                    <?php if (!empty($categories)): ?>
                        <ul class="product-category">
                            <?php foreach ($categories as $cat): ?>
                                <li>
                                    <a href="products?category=<?= $cat['id'] ?>">
                                        <?= htmlspecialchars($cat['name']) ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </ul>

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
                                <a href="profile" class="dropdown-item">Thông tin cá nhân</a>
                                <a href="orders" class="dropdown-item"><i class="fas fa-shopping-bag"></i> Đơn hàng</a>
                                <a href="settings" class="dropdown-item">Cài đặt</a>
                                <div class="dropdown-divider"></div>
                                <a href="logout" class="dropdown-item logout">Đăng xuất</a>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="auth-buttons">
                            <a href="login" class="btn btn-login">
                                 Đăng nhập
                            </a>
                            <a href="register" class="btn btn-register">
                                 Đăng ký
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
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

        <!-- <button class="mobile-menu-toggle" onclick="toggleMobileMenu()">
            <i class="fas fa-bars"></i>
        </button> -->
    </div>

    <!-- Mobile Navigation -->
     <?php /*
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
    */ ?>
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
