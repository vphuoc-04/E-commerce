<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="http://localhost/WEBBANHANG/views/admin/css/component.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
    <aside>
        <?php 
            include 'views/constants/admin/menu.php'; 
            $currentPage = getCurrentPage();
        ?>
        <?php foreach ($menu as $section): ?>
            <h4><?= $section['label'] ?></h4>
            <ul>
                <?php foreach ($section['items'] as $item): ?>
                    <?php 
                        $isActive = in_array($currentPage, $item['active']) ? 'active' : '';
                        $hasSub = !empty($item['links']);
                        $isParentActive = $hasSub && in_array($currentPage, array_merge(...array_column($item['links']['items'], 'active')));
                    ?>
                    <li class="<?= $hasSub ? 'has-sub' : '' ?> <?= $isActive || $isParentActive ? 'open active' : '' ?>">
                        <a href="<?= $item['to'] ?>" class="<?= $isActive ?>">
                            <i class="<?= $item['icon'] ?>"></i> <?= $item['label'] ?>
                        </a>

                        <?php if ($hasSub): ?>
                            <ul class="submenu" style="<?= $isParentActive ? 'display:block;' : '' ?>">
                                <?php foreach ($item['links']['items'] as $sub): ?>
                                    <?php 
                                        $isSubActive = in_array($currentPage, $sub['active']) ? 'active' : '';
                                    ?>
                                    <li>
                                        <a href="<?= $sub['to'] ?>" class="<?= $isSubActive ?>">
                                            <i class="<?= $sub['icon'] ?>"></i> <?= $sub['label'] ?>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endforeach; ?>
    </aside>

    <script>
    document.querySelectorAll(".has-sub > a").forEach(a => {
            a.addEventListener("click", e => {
                e.preventDefault();
                a.parentElement.classList.toggle("open");
            });
        });
    </script>
</body>
</html>
