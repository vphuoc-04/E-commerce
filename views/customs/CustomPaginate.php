<?php
$currentPage = $pagination["page"];
$totalPages  = $pagination["total_pages"];
$baseUrl = basename($_SERVER['PHP_SELF']);; 
$pageParam = $_GET['page'];
?>
<link rel="stylesheet" href="http://localhost/WEBBANHANg/views/customs/CustomPaginate.css">

<div class="pagination">
    <?php if ($currentPage > 1): ?>
        <a href="<?= $baseUrl ?>?page=<?= $pageParam ?>&page_number=<?= $currentPage - 1 ?>">&laquo; Trước</a>
    <?php endif; ?>

    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
        <a href="<?= $baseUrl ?>?page=<?= $pageParam ?>&page_number=<?= $i ?>" 
           class="<?= $i == $currentPage ? 'active' : '' ?>">
            <?= $i ?>
        </a>
    <?php endfor; ?>

    <?php if ($currentPage < $totalPages): ?>
        <a href="<?= $baseUrl ?>?page=<?= $pageParam ?>&page_number=<?= $currentPage + 1 ?>">Sau &raquo;</a>
    <?php endif; ?>
</div>
