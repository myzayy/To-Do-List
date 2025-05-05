<?php

namespace App\Models;

use Config\Database;
use PDO;

class Task
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function all(): array
    {
        $stmt = $this->db->query("SELECT * FROM tasks ORDER BY created_at DESC");
        return $stmt->fetchAll();
    }

    public function create(string $title, string $description): void
    {
        $stmt = $this->db->prepare("INSERT INTO tasks (title, description) VALUES (?, ?)");
        $stmt->execute([$title, $description]);
    }

    public function update(int $id, string $title, string $description): void
    {
        $stmt = $this->db->prepare("UPDATE tasks SET title = ?, description = ? WHERE id = ?");
        $stmt->execute([$title, $description, $id]);
    }

    public function delete(int $id): void
    {
        $stmt = $this->db->prepare("DELETE FROM tasks WHERE id = ?");
        $stmt->execute([$id]);
    }

    public function get(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM tasks WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }
}
