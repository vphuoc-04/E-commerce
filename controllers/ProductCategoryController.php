<?php
require_once __DIR__ . '/../models/ProductCategory.php';
include 'BaseController.php';

class ProductCategoryController extends BaseController {

    public function index($page = 1, $limit = 5) {
        $page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int) $_GET['page'] : $page;
        $limit = isset($_GET['limit']) && is_numeric($_GET['limit']) ? (int) $_GET['limit'] : $limit;

        $query = "SELECT * FROM product_categories ORDER BY created_at DESC";
        $pagination = $this->basePagination($query, [], $page, $limit);
        $categories = array_map(fn($row) => new ProductCategory($row), $pagination['data']);

        return [
            'categories' => $categories,
            'pagination' => [
                'total' => $pagination['total'],
                'page' => $pagination['page'],
                'limit' => $pagination['limit'],
                'total_pages' => $pagination['total_pages'],
            ]
        ];
    }

    public function show($id) {
        $pdo = Database::connect();
        $stmt = $pdo->prepare("SELECT * FROM product_categories WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            return new ProductCategory($row);
        }
        return null;
    }

    public function store(array $data) {
        $pdo = Database::connect();
        // Duplicate check by name
        if (!empty($data['name']) && class_exists('ProductCategoryRepository')) {
            $exists = ProductCategoryRepository::findByName($data['name']);
            if ($exists) {
                http_response_code(409);
                return [ 'error' => 'duplicate', 'field' => 'name', 'message' => 'Tên danh mục đã tồn tại' ];
            }
        }
        $stmt = $pdo->prepare(
            "
            INSERT INTO product_categories (name, description, created_at)
            VALUES (:name, :description, NOW())
        "
        );
        $stmt->execute([
            'name'        => $data['name'],
            'description' => $data['description'] ?? null,
        ]);

        return $this->show($pdo->lastInsertId());
    }

    public function update($id, array $data) {
        $pdo = Database::connect();
        $stmt = $pdo->prepare(
            "
            UPDATE product_categories SET 
                name = :name,
                description = :description,
                updated_at = NOW()
            WHERE id = :id
        "
        );
        $stmt->execute([
            'id'          => $id,
            'name'        => $data['name'],
            'description' => $data['description'] ?? null,
        ]);

        return $this->show($id);
    }

    public function destroy($id) {
        $pdo = Database::connect();
        $stmt = $pdo->prepare("DELETE FROM product_categories WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
