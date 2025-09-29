<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title><?php echo $title ?? "Trang chủ"; ?></title>
    <link rel="stylesheet" href="http://localhost/WEBBANHANG/views/admin/css/component.css">
</head>
<body>
    <div class="container">
        <?php include 'aside.php'; ?>
        <div class="headerAndContent">
            <?php include 'header.php'; ?>
            <?php if (isset($breadcrumb)) include 'breadcrumb.php'; ?>
            <main>
                <?php include $content; ?>
            </main>
        </div>
    </div>
    <!-- <?php include 'footer.php'; ?> -->

    <?php
    if (!isset($_COOKIE['token'])) {
        header("Location: /WEBBANHANG/login");
        exit();
    }

    $token = $_COOKIE['token']; 
    ?>

</body>
</html>
