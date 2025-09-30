<?php

class BaseController {
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
        if ($page > $totalPages) $page = $totalPages; // tránh page vượt quá
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
}
