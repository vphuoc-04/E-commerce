<?php
// views/customs/CustomFilter.php

if (!isset($filterFields)) {
    $filterFields = [];
}

if (!isset($currentFilters)) {
    $currentFilters = $_GET ?? [];
}

$availableFilterTypes = [
    'text' => 'text',
    'select' => 'select',
    'date' => 'date',
    'number' => 'number'
];

// Hàm build URL chỉ với các filter có giá trị, LUÔN có page
function buildCleanUrl($page = 1) {
    $queryParams = ['page' => $page];
    
    // Chỉ thêm các param có giá trị khác rỗng (trừ page)
    foreach ($_GET as $key => $value) {
        if (trim($value) !== '' && $key !== 'page') {
            $queryParams[$key] = $value;
        }
    }
    
    return '?' . http_build_query($queryParams);
}

// Hàm build URL khi xóa một filter
function buildUrlWithoutFilter($filterToRemove) {
    $queryParams = ['page' => 1]; // Về page 1 khi xóa filter
    
    foreach ($_GET as $key => $value) {
        if (trim($value) !== '' && $key !== $filterToRemove && $key !== 'page') {
            $queryParams[$key] = $value;
        }
    }
    
    return '?' . http_build_query($queryParams);
}

// Hàm build URL khi chuyển page (giữ nguyên filter)
function buildPageUrl($page) {
    $queryParams = ['page' => $page];
    
    // Giữ lại tất cả filter hiện tại
    foreach ($_GET as $key => $value) {
        if (trim($value) !== '' && $key !== 'page') {
            $queryParams[$key] = $value;
        }
    }
    
    return '?' . http_build_query($queryParams);
}
?>

<link rel="stylesheet" href="http://localhost/WEBBANHANG/views/customs/css/CustomFilter.css">

<div class="custom-filter-container">
    <form method="GET" action="" class="filter-form" id="filterForm">
        <!-- Hidden field để đảm bảo LUÔN có page=1 khi filter -->
        <input type="hidden" name="page" value="1" id="pageInput">
        
        <?php foreach ($filterFields as $field): ?>
            <?php
            $fieldName = $field['name'] ?? '';
            $fieldLabel = $field['label'] ?? $fieldName;
            $fieldType = $field['type'] ?? 'text';
            $fieldOptions = $field['options'] ?? [];
            $fieldPlaceholder = $field['placeholder'] ?? "Tìm theo {$fieldLabel}...";
            $currentValue = $currentFilters[$fieldName] ?? '';
            ?>
            
            <div class="filter-group">
                <label for="filter-<?= $fieldName ?>"><?= htmlspecialchars($fieldLabel) ?>:</label>
                
                <?php if ($fieldType === 'select' && !empty($fieldOptions)): ?>
                    <select name="<?= $fieldName ?>" id="filter-<?= $fieldName ?>" class="filter-input">
                        <option value="">Tất cả</option>
                        <?php foreach ($fieldOptions as $optionValue => $optionLabel): ?>
                            <option value="<?= htmlspecialchars($optionValue) ?>" 
                                <?= $currentValue == $optionValue ? 'selected' : '' ?>>
                                <?= htmlspecialchars($optionLabel) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                
                <?php else: ?>
                    <input 
                        type="<?= $availableFilterTypes[$fieldType] ?? 'text' ?>"     
                        name="<?= $fieldName ?>"   
                        id="filter-<?= $fieldName ?>"   
                        value="<?= htmlspecialchars($currentValue) ?>"  
                        placeholder="<?= htmlspecialchars($fieldPlaceholder) ?>"
                        class="filter-input"
                    >
                <?php endif; ?>
            </div>
        <?php endforeach; ?>

    </form>
    <?php
    $activeFilters = array_filter($currentFilters, function($value, $key) use ($filterFields) {
        $filterFieldNames = array_column($filterFields, 'name');
        return in_array($key, $filterFieldNames) && trim($value) !== '' && $key !== 'page';
    }, ARRAY_FILTER_USE_BOTH);
    ?>
    
    <?php if (!empty($activeFilters)): ?>
    <div class="active-filters">
        <strong>Bộ lọc đang áp dụng:</strong>
        <?php foreach ($activeFilters as $field => $value): 
            $fieldLabel = '';
            foreach ($filterFields as $filterField) {
                if ($filterField['name'] === $field) {
                    $fieldLabel = $filterField['label'] ?? $field;
                    break;
                }
            }
        ?>
            <span class="filter-tag">
                <?= htmlspecialchars($fieldLabel) ?>: <?= htmlspecialchars($value) ?>
                <a href="<?= buildUrlWithoutFilter($field) ?>" class="remove-filter" title="Xóa bộ lọc">×</a>
            </span>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterForm = document.getElementById('filterForm');
    const pageInput = document.getElementById('pageInput');
    
    if (filterForm) {
        // Khi submit form filter, LUÔN về page 1
        filterForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const params = new URLSearchParams();
            
            // LUÔN thêm page=1 khi filter
            params.append('page', '1');
            
            // Chỉ thêm các field có giá trị (trừ page)
            for (let [key, value] of formData.entries()) {
                if (value.trim() !== '' && key !== 'page') {
                    params.append(key, value);
                }
            }
            
            // Chuyển hướng đến URL với page=1
            const queryString = params.toString();
            const newUrl = `?${queryString}`;
            window.location.href = newUrl;
        });
        
        // Xử lý sự kiện cho nút Xóa lọc
        const clearBtn = document.querySelector('.btn-clear');
        if (clearBtn) {
            clearBtn.addEventListener('click', function(e) {
                e.preventDefault();
                // Khi xóa lọc, về page=1
                window.location.href = '?page=1';
            });
        }
        
        // Theo dõi thay đổi trên các input filter
        const filterInputs = document.querySelectorAll('.filter-input');
        filterInputs.forEach(input => {
            input.addEventListener('change', function() {
                // Khi thay đổi filter, tự động submit form (về page=1)
                filterForm.requestSubmit();
            });
        });
    }
});
</script>