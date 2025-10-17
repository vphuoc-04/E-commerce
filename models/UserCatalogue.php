<?php
require_once "User.php";

class UserCatalogue {
    public ?int $id;
    public ?string $name;
    public ?string $description;
    public ?int $addedBy;
    public ?int $updatedBy;  
    public ?DateTime $createdAt;
    public ?DateTime $updatedAt;

    public function __construct(array $data = []) {
        $this->id          = $data['id'] ?? null;
        $this->name        = $data['name'] ?? null;
        $this->description = $data['description'] ?? null;

        $this->addedBy   = $data['added_by'] ?? null;
        $this->updatedBy = $data['updated_by'] ?? null;

        $this->createdAt   = isset($data['created_at']) ? new DateTime($data['created_at']) : null;
        $this->updatedAt   = isset($data['updated_at']) ? new DateTime($data['updated_at']) : null;
    }
}
