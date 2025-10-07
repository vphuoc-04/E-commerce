<?php
require_once "User.php";
require_once "ProductCategory.php";

class Product {
    public ?int $id;
    public ?string $name;
    public ?string $description;
    public ?float $price;
    public ?string $image;
    public ?ProductCategory $categoryName;
    public ?User $addedBy;
    public ?User $updatedBy;
    public ?DateTime $createdAt;
    public ?DateTime $updatedAt;

    public function __construct(array $data = []) {
        $this->id          = $data['id'] ?? null;
        $this->name        = $data['name'] ?? null;
        $this->description = $data['description'] ?? null;
        $this->price       = isset($data['price']) ? (float)$data['price'] : 0;
        $this->image       = $data['image'] ?? null;

        // Nếu bạn có join với bảng product_categories
        $this->category = null;
        if (isset($data['category'])) {
            // Nếu là mảng thông tin đầy đủ của category
            if (is_array($data['category'])) {
                $this->category = new ProductCategory($data['category']);
            } else {
                // Nếu chỉ có id
                $this->category = new ProductCategory(['id' => $data['category']]);
            }
        } elseif (isset($data['category_id'])) {
            $this->category = new ProductCategory(['id' => $data['category_id']]);
        }

        // --- Xác định người đăng sản phẩm ---
        $this->addedBy = null;
        if (isset($data['added_by'])) {
            if (is_array($data['added_by'])) {
                $this->addedBy = new User($data['added_by']);
            } else {
                $this->addedBy = new User(['id' => $data['added_by']]);
            }
        }

        // --- Xác định người sửa sản phẩm ---
        $this->updatedBy = null;
        if (isset($data['updated_by'])) {
            if (is_array($data['updated_by'])) {
                $this->updatedBy = new User($data['updated_by']);
            } else {
                $this->updatedBy = new User(['id' => $data['updated_by']]);
            }
        }

        $this->createdAt = isset($data['created_at']) ? new DateTime($data['created_at']) : null;
        $this->updatedAt = isset($data['updated_at']) ? new DateTime($data['updated_at']) : null;
    }
}
