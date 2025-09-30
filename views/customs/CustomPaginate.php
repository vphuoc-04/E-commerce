<?php
$currentPage = $pagination["page"] ?? 1;
$totalPages  = $pagination["total_pages"] ?? 1;

$pageName = $model;

function createPaginationUrl($pageName, $pageNumber) {
    return "$pageName?page=$pageNumber";
}
?>

<link rel="stylesheet" href="http://localhost/WEBBANHANG/views/customs/CustomPaginate.css">

<div class="pagination">
    <?php if ($currentPage > 1): ?>
        <a href="<?= createPaginationUrl($pageName, $currentPage - 1) ?>">&laquo; Trước</a>
    <?php endif; ?>

    <?php
    $range = 2; // số trang hiển thị trước/sau trang hiện tại
    
    // luôn hiển thị trang đầu
    if ($currentPage > 1 + $range) {
        echo '<a href="' . createPaginationUrl($pageName, 1) . '">1</a>';
        if ($currentPage > 2 + $range) {
            echo '<span class="dots">...</span>';
        }
    }

    // hiển thị các trang xung quanh currentPage
    for ($i = max(1, $currentPage - $range); $i <= min($totalPages, $currentPage + $range); $i++) {
        echo '<a href="' . createPaginationUrl($pageName, $i) . '" 
                class="' . ($i == $currentPage ? 'active' : '') . '">' . $i . '</a>';
    }

    // luôn hiển thị trang cuối
    if ($currentPage < $totalPages - $range) {
        if ($currentPage < $totalPages - $range - 1) {
            echo '<span class="dots">...</span>';
        }
        echo '<a href="' . createPaginationUrl($pageName, $totalPages) . '">' . $totalPages . '</a>';
    }
    ?>

    <?php if ($currentPage < $totalPages): ?>
        <a href="<?= createPaginationUrl($pageName, $currentPage + 1) ?>">Sau &raquo;</a>
    <?php endif; ?>
</div>
