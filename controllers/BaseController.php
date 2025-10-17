<?php

class BaseController {
    protected $baseApiUrl = "http://localhost/webbanhang/apis/";
    protected $apiFileName;
    
    public function basePagination($query, $params = [], $page = 1, $limit = 10) {
        $pdo = Database::connect();

        // Ép kiểu cho chắc
        $page  = max(1, (int)$page);
        $limit = max(1, (int)$limit);

        // Đếm tổng bản ghi
        $countQuery = "SELECT COUNT(*) as total FROM (" . $query . ") as sub";
        $countStmt = $pdo->prepare($countQuery);
        $countStmt->execute($params);
        $total = (int)$countStmt->fetch(PDO::FETCH_ASSOC)['total'];

        // Tính toán phân trang
        $totalPages = $limit > 0 ? ceil($total / $limit) : 1;
        if ($page > $totalPages) $page = $totalPages;
        $offset = ($page - 1) * $limit;

        // Query có limit/offset
        $pagedQuery = $query . " LIMIT :limit OFFSET :offset";
        $stmt = $pdo->prepare($pagedQuery);

        foreach ($params as $key => $value) {
            $stmt->bindValue(is_int($key) ? $key+1 : $key, $value);
        }

        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);

        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return [
            'data' => $data,
            'total' => $total,
            'page' => $page,
            'limit' => $limit,
            'total_pages' => $totalPages
        ];
    }

    //Lấy giá trị từ query string với filter
    public function getField($fieldName, $default = '') {
        return isset($_GET[$fieldName]) ? trim($_GET[$fieldName]) : $default;
    }

    //Lấy page từ query string, LUÔN có page (mặc định = 1)
    public function getPage() {
        return isset($_GET['page']) && is_numeric($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
    }

    //Tạo điều kiện WHERE và parameters cho filter
    public function buildFilterConditions($allowedFields = []) {
        $conditions = [];
        $params = [];
        
        foreach ($allowedFields as $field => $type) {
            $value = $this->getField($field);
            
            if ($value !== '') {
                switch ($type) {
                    case 'like':
                        $conditions[] = "$field LIKE :$field";
                        $params[$field] = "%$value%";
                        break;
                    case 'exact':
                        $conditions[] = "$field = :$field";
                        $params[$field] = $value;
                        break;
                    case 'date':
                        $conditions[] = "DATE($field) = :$field";
                        $params[$field] = $value;
                        break;
                    case 'number':
                        if (is_numeric($value)) {
                            $conditions[] = "$field = :$field";
                            $params[$field] = $value;
                        }
                        break;
                }
            }
        }
        
        $whereClause = '';
        if (!empty($conditions)) {
            $whereClause = ' WHERE ' . implode(' AND ', $conditions);
        }
        
        return [
            'where' => $whereClause,
            'params' => $params
        ];
    }


    //Tạo URL với các tham số filter (LUÔN có page)
    public function buildCleanFilterUrl($baseUrl, $filterFields = [], $page = null) {
        $queryParams = [];
        
        // LUÔN có page, mặc định = 1
        $currentPage = $page !== null ? $page : $this->getPage();
        $queryParams['page'] = $currentPage;
        
        // Thêm các filter có giá trị
        foreach ($filterFields as $field) {
            $fieldName = is_array($field) ? $field['name'] : $field;
            $value = $this->getField($fieldName);
            if ($value !== '') {
                $queryParams[$fieldName] = $value;
            }
        }
        
        return $baseUrl . '?' . http_build_query($queryParams);
    }

    //Build URL cho phân trang (giữ nguyên filter)
    public function buildPageUrl($page, $filterFields = []) {
        $queryParams = ['page' => $page];
        
        // Giữ lại tất cả filter hiện tại
        foreach ($filterFields as $field) {
            $fieldName = is_array($field) ? $field['name'] : $field;
            $value = $this->getField($fieldName);
            if ($value !== '') {
                $queryParams[$fieldName] = $value;
            }
        }
        
        return '?' . http_build_query($queryParams);
    }

    //Build URL xóa filter (về page=1)
    public function buildClearFilterUrl() {
        return '?page=1';
    }

    // Build API URL với filter (LUÔN có page)
    public function buildApiUrl($route = 'index', $page = null, $filterFields = []) {
        if (empty($this->apiFileName)) {
            throw new Exception("apiFileName chưa được định nghĩa trong controller");
        }
        
        $queryParams = ['route' => $route];
        
        // LUÔN có page, mặc định lấy từ query string
        $currentPage = $page !== null ? $page : $this->getPage();
        $queryParams['page'] = $currentPage;
        
        // Thêm các filter có giá trị
        foreach ($filterFields as $field) {
            $fieldName = is_array($field) ? $field['name'] : $field;
            $value = $this->getField($fieldName);
            if ($value !== '') {
                $queryParams[$fieldName] = $value;
            }
        }
        
        return $this->baseApiUrl . $this->apiFileName . "?" . http_build_query($queryParams);
    }

    
    //Lấy API endpoint - có thể override trong controller con
    protected function getApiEndpoint() {
        if (empty($this->apiFileName)) {
            return $this->baseApiUrl . "BaseApi.php";
        }
        return $this->baseApiUrl . $this->apiFileName;
    }

    //Lấy cấu hình filter fields - nên override trong controller con
    protected function getFilterFieldsConfig() {
        return [];
    }

    //Lấy các filter hiện tại (chỉ những cái có giá trị)
    protected function getCurrentFilters($filterFields = []) {
        $filters = [];
        $fieldsToCheck = !empty($filterFields) ? $filterFields : array_keys($this->getFilterFieldsConfig());
        
        foreach ($fieldsToCheck as $field) {
            $fieldName = is_array($field) ? $field['name'] : $field;
            $value = $this->getField($fieldName);
            if ($value !== '') {
                $filters[$fieldName] = $value;
            }
        }
        return $filters;
    }

    //Kiểm tra xem có filter đang được áp dụng không
    protected function hasActiveFilters($filterFields = []) {
        return !empty($this->getCurrentFilters($filterFields));
    }

    // Lấy thông tin thông qua FK
    // protected function fetchForeignKeyData($table, $foreignKeyField, $foreignKeyValue) {
    //     $pdo = Database::connect();
    //     $stmt = $pdo->prepare("SELECT * FROM {$table} WHERE id = ?");
    //     $stmt->execute([$foreignKeyValue]);
    //     return $stmt->fetch(PDO::FETCH_ASSOC);
    // }
}