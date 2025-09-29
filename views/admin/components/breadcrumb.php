<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="http://localhost/WEBBANHANG/views/admin/css/component.css">
</head>
<body>
    <?php
        // Kiểm tra dữ liệu breadcrumb
        if (!isset($breadcrumb) || !is_array($breadcrumb)) {
            $breadcrumb = ["pageTitle" => "", "items" => []];
        }
    ?>

    <div class="breadcrumb">
        <!-- Title -->
        <?php if (!empty($breadcrumb["pageTitle"])): ?>
            <div class="title">
                <h2 class="breadcrumb-title"><?= $breadcrumb["pageTitle"] ?></h2>
            </div>
        <?php endif; ?>

        <!-- Items -->
        <div class="route">
            <?php foreach ($breadcrumb["items"] as $index => $item): ?>
                <?php if (isset($item["url"])): ?>
                    <a href="<?= $item["url"] ?>"><?= $item["label"] ?></a>
                <?php else: ?>
                    <span><?= $item["label"] ?></span>
                <?php endif; ?>

                <?php if ($index < count($breadcrumb["items"]) - 1): ?>
                    <span>›</span>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>
