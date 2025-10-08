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
                        $isOpen = $isParentActive; // Tách biệt open và active
                    ?>
                    <li class="<?= $hasSub ? 'has-sub' : '' ?> <?= $isOpen ? 'open' : '' ?> <?= $isActive ? 'active' : '' ?>">
                        <a href="<?= $hasSub ? '#' : $item['to'] ?>" class="<?= $isActive ?>">
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
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll(".has-sub > a").forEach(a => {
            a.addEventListener("click", function(e) {
                // Chỉ xử lý toggle khi có submenu, không chuyển trang
                if (this.parentElement.classList.contains('has-sub')) {
                    e.preventDefault();
                    const parentLi = this.parentElement;
                    
                    // Toggle trạng thái open
                    parentLi.classList.toggle("open");
                    
                    // Đóng/mở submenu
                    const submenu = parentLi.querySelector('.submenu');
                    if (submenu) {
                        if (parentLi.classList.contains('open')) {
                            submenu.style.display = 'block';
                        } else {
                            submenu.style.display = 'none';
                        }
                    }
                }
            });
        });

        // Giữ submenu mở nếu có item con active (có thể đóng bằng click)
        document.querySelectorAll('.has-sub.active').forEach(activeItem => {
            if (!activeItem.classList.contains('open')) {
                activeItem.classList.add('open');
                const submenu = activeItem.querySelector('.submenu');
                if (submenu) {
                    submenu.style.display = 'block';
                }
            }
        });
    });
    </script>
</body>
</html>