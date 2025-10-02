<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title><?php echo $title ?? "Trang chủ"; ?></title>
    <link rel="stylesheet" href="http://localhost/WEBBANHANG/views/client/css/component.css">
</head>
<body>
    <div class="container">
        <?php include 'header.php'; ?>
        <?php include $content; ?>
        <?php include 'footer.php'; ?>
    </div>
</body>
</html>
