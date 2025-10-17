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
<script src="http://localhost/WEBBANHANG/views/customs/js/custom-table.js"></script>

<div class="custom-table-container">
    <div class="custom-table-content">
    <div class="describte">
        <?php foreach ($describe as $desc): ?>
            <h3><?= htmlspecialchars($desc["title"]) ?></h3>
            <p><?= htmlspecialchars($desc["description"]) ?></p>
        <?php endforeach; ?>
    </div>

    <?php if (isset($buttonAction) && !empty($buttonAction)): ?>
        <div class="custom-table-top-actions" style="margin: 12px 0; display: flex; gap: 8px;">
            <?php foreach ($buttonAction as $action): ?>
                <?php
                    $label = $action["label"] ?? "Action";
                    $icon = $action["icon"] ?? "";
                    $className = $action["className"] ?? "btn";
                    $onClick = $action["onClick"] ?? null;
                    $path = $action["path"] ?? null;
                ?>
                <?php if ($onClick): ?>
                    <button type="button" class="<?= htmlspecialchars($className) ?>" onclick="<?= $onClick ?>">
                        <?= $icon ?><span style="margin-left:6px;"><?= htmlspecialchars($label) ?></span>
                    </button>
                <?php elseif ($path): ?>
                    <a href="<?= $path ?>" class="<?= htmlspecialchars($className) ?>">
                        <?= $icon ?><span style="margin-left:6px;"><?= htmlspecialchars($label) ?></span>
                    </a>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <?php if ($showFilter && !empty($filterFields)): ?>
        <?php include 'CustomFilter.php';?>
    <?php endif; ?>

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
                        <div class="button-table-action">
                            <?php foreach ($buttonTableActions as $action): ?>
                                <?php if (isset($action["path"])): ?>
                                    <!-- Nút có đường dẫn (Edit) -->
                                    <a href="<?= $action["path"] ?>?id=<?= $row["id"] ?>" 
                                    class="table-btn <?= str_replace('btn ', '', $action["className"]) ?>" 
                                    title="<?= $action["method"] ?? '' ?>">
                                        <?= $action["icon"] ?>
                                    </a>
                                <?php else: ?>
                                    <!-- Nút không có đường dẫn (Delete) -->
                                    <button type="button" 
                                    class="table-btn <?= str_replace('btn ', '', $action["className"]) ?>" 
                                    title="<?= $action["method"] ?? '' ?>"
                                    onclick="<?= str_replace('%id%', $row['id'], $action["onClick"] ?? '') ?>">
                                        <?= $action["icon"] ?>
                                    </button>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php include 'CustomPaginate.php'; ?>
    </div>
</div>