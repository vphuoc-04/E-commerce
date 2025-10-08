<?php
require_once __DIR__ . '/../models/Product.php';
include 'BaseController.php';

class ProductController extends BaseController {

    public function index($page = 1, $limit = 10) {
        $page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int) $_GET['page'] : $page;
        $limit = isset($_GET['limit']) && is_numeric($_GET['limit']) ? (int) $_GET['limit'] : $limit;

        // JOIN với bảng product_categories để lấy tên danh mục
        $query = "
            SELECT 
                p.*, 
                c.id AS category_ref_id, 
                c.name AS category_name, 
                c.description AS category_description
            FROM products p
            LEFT JOIN product_categories c ON p.category_id = c.id
            ORDER BY p.created_at DESC
        ";

        $pagination = $this->basePagination($query, [], $page, $limit);

        // Khởi tạo đối tượng Product cho từng dòng
        $products = array_map(fn($row) => new Product($row), $pagination['data']);

        return [
            'products' => $products,
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
        $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            return new Product($row);
        }
        return null;
    }

    public function store(array $data, $file = null) {
        $pdo = Database::connect();
        $userId = $_SESSION['user']['id'] ?? null; // Lấy ID người đăng hiện tại

        // Xử lý ảnh upload
        $imagePath = null;
        if ($file && isset($file['tmp_name']) && $file['tmp_name'] !== '') {
            $uploadDir = __DIR__ . '/../../public/uploads/products/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $fileName = time() . '_' . basename($file['name']);
            $targetPath = $uploadDir . $fileName;

            if (move_uploaded_file($file['tmp_name'], $targetPath)) {
                $imagePath = 'uploads/products/' . $fileName;
            }
        }

        $stmt = $pdo->prepare("
            INSERT INTO products (name, description, price, image, category_id, added_by, created_at)
            VALUES (:name, :description, :price, :image, :category_id, :added_by, NOW())
        ");
        $stmt->execute([
            'name'        => $data['name'],
            'description' => $data['description'] ?? null,
            'price'       => $data['price'] ?? 0,
            'image'       => $imagePath,
            'category_id' => $data['category_id'] ?? null,
            'added_by'    => $userId,
        ]);

        return $this->show($pdo->lastInsertId());
    }

    public function update($id, array $data, $file = null) {
        $pdo = Database::connect();
        $userId = $_SESSION['user']['id'] ?? null; // Ai là người sửa?

        $current = $this->show($id);
        $imagePath = $current->image ?? null;

        // Nếu có ảnh mới, thì upload và xóa ảnh cũ
        if ($file && isset($file['tmp_name']) && $file['tmp_name'] !== '') {
            $uploadDir = __DIR__ . '/../../public/uploads/products/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $fileName = time() . '_' . basename($file['name']);
            $targetPath = $uploadDir . $fileName;

            if (move_uploaded_file($file['tmp_name'], $targetPath)) {
                // Xóa ảnh cũ
                if ($imagePath && file_exists(__DIR__ . '/../../public/' . $imagePath)) {
                    unlink(__DIR__ . '/../../public/' . $imagePath);
                }
                $imagePath = 'uploads/products/' . $fileName;
            }
        }

        $stmt = $pdo->prepare("
            UPDATE products SET 
                name = :name,
                description = :description,
                price = :price,
                image = :image,
                category_id = :category_id,
                updated_by = :updated_by,
                updated_at = NOW()
            WHERE id = :id
        ");
        $stmt->execute([
            'id'          => $id,
            'name'        => $data['name'],
            'description' => $data['description'] ?? null,
            'price'       => $data['price'] ?? 0,
            'image'       => $imagePath,
            'category_id' => $data['category_id'] ?? null,
            'updated_by'  => $userId,
        ]);

        return $this->show($id);
    }

    public function destroy($id) {
        $pdo = Database::connect();
        $product = $this->show($id);

        if ($product && $product->image && file_exists(__DIR__ . '/../../public/' . $product->image)) {
            unlink(__DIR__ . '/../../public/' . $product->image);
        }

        $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
