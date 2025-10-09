<?php
// views/customs/CustomTable.php

if (!isset($columns) || !isset($data)) {
    echo "<p style='color:red'>Thiếu dữ liệu để render bảng</p>";
    return;
}

// Default values for optional parameters
if (!isset($filterFields)) {
    $filterFields = [];
}

if (!isset($showFilter)) {
    $showFilter = !empty($filterFields);
}

if (!isset($currentFilters)) {
    $currentFilters = $_GET ?? [];
}
?>
<link rel="stylesheet" href="http://localhost/WEBBANHANG/views/customs/css/CustomTable.css">

<?php include __DIR__ . '/../states/DataChangeState.php'; ?>

<div class="custom-table-container">
    <!-- Include Filter Component -->
    <?php if ($showFilter && !empty($filterFields)): ?>
        <?php include 'CustomFilter.php';?>
    <?php endif; ?>

    <div class="describte">
        <?php foreach ($describe as $desc): ?>
            <h3><?= htmlspecialchars($desc["title"]) ?></h3>
            <p><?= htmlspecialchars($desc["description"]) ?></p>
        <?php endforeach; ?>
    </div>

    <table class="custom-table">
        <thead>
            <tr>
                <th></th>
                <?php foreach ($table as $column): ?>
                    <th class="<?= $column["className"] ?? "" ?>">
                        <?= htmlspecialchars($column["name"]) ?>
                    </th>
                <?php endforeach; ?>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($data as $row): ?>
                <tr>
                    <td><input type="checkbox"></td>
                    <?php foreach ($table as $column): ?>
                        <td class="<?= $column["className"] ?? "" ?>">
                            <?= $column["render"]($row) ?>
                        </td>
                    <?php endforeach; ?>
                    <td>
                        <div class="button-action">
                            <?php foreach ($buttonActions as $action): ?>
                                <a href="<?= $action["path"] ?>?id=<?= $row["id"] ?>" class="<?= $action["className"] ?>">
                                    <?= $action["icon"] ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php include 'CustomPaginate.php'; ?>
</div>