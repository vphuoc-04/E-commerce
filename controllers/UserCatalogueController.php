<?php
require_once __DIR__ . '/../models/UserCatalogue.php';

include 'BaseController.php';

class UserCatalogueController extends BaseController {

    public function index($page = 1, $limit = 5) {
        $page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int) $_GET['page'] : $page;
        $limit = isset($_GET['limit']) && is_numeric($_GET['limit']) ? (int) $_GET['limit'] : $limit;
        $query = "SELECT * FROM user_catalogues ORDER BY created_at ASC";
        $pagination = $this->basePagination($query, [], $page, $limit);
        $catalogues = array_map(fn($row) => new UserCatalogue($row), $pagination['data']);

        return [
            'catalogues' => $catalogues,
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
        $stmt = $pdo->prepare("SELECT * FROM user_catalogues WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            return new UserCatalogue($row);
        }
        return null;
    }

    public function store(array $data) {
        $pdo = Database::connect();
        $stmt = $pdo->prepare("
            INSERT INTO user_catalogues (name, description, created_at) 
            VALUES (:name, :description, NOW())
        ");
        $stmt->execute([
            'name'        => $data['name'],
            'description' => $data['description'] ?? null,
        ]);

        return $this->show($pdo->lastInsertId());
    }

    public function update($id, array $data) {
        $pdo = Database::connect();
        $stmt = $pdo->prepare("
            UPDATE user_catalogues SET 
                name = :name,
                description = :description,
                updated_at = NOW()
            WHERE id = :id
        ");
        $stmt->execute([
            'id'          => $id,
            'name'        => $data['name'],
            'description' => $data['description'] ?? null,
        ]);

        return $this->show($id);
    }

    public function destroy($id) {
        $pdo = Database::connect();
        $stmt = $pdo->prepare("DELETE FROM user_catalogues WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
