<?php declare(strict_types=1);

namespace App\Models;

use PDO;

final class Project {
    public function __construct(private PDO $pdo) {}

    public function getAll(): array {
        $stmt = $this->pdo->query('
            SELECT p.*, a.username as created_by_name 
            FROM projects p 
            LEFT JOIN admins a ON p.created_by = a.id 
            ORDER BY p.created_at DESC
        ');
        return $stmt->fetchAll() ?: [];
    }

    public function getById(int $id): ?array {
        $stmt = $this->pdo->prepare('
            SELECT p.*, a.username as created_by_name 
            FROM projects p 
            LEFT JOIN admins a ON p.created_by = a.id 
            WHERE p.id = ?
        ');
        $stmt->execute([$id]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    public function create(array $data): int {
        $stmt = $this->pdo->prepare('
            INSERT INTO projects (name, description, status, budget, start_date, end_date, created_by) 
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ');
        $stmt->execute([
            $data['name'],
            $data['description'],
            $data['status'],
            $data['budget'],
            $data['start_date'],
            $data['end_date'],
            $data['created_by']
        ]);
        return (int) $this->pdo->lastInsertId();
    }

    public function update(int $id, array $data): bool {
        $stmt = $this->pdo->prepare('
            UPDATE projects 
            SET name = ?, description = ?, status = ?, budget = ?, start_date = ?, end_date = ? 
            WHERE id = ?
        ');
        return $stmt->execute([
            $data['name'],
            $data['description'],
            $data['status'],
            $data['budget'],
            $data['start_date'],
            $data['end_date'],
            $id
        ]);
    }

    public function delete(int $id): bool {
        $stmt = $this->pdo->prepare('DELETE FROM projects WHERE id = ?');
        return $stmt->execute([$id]);
    }

    public function getStats(): array {
        $stats = [];
        
        // Liczba projektów według statusu
        $stmt = $this->pdo->query('SELECT status, COUNT(*) as count FROM projects GROUP BY status');
        $statusCounts = $stmt->fetchAll();
        $stats['by_status'] = array_column($statusCounts, 'count', 'status');
        
        // Całkowity budżet
        $stmt = $this->pdo->query('SELECT SUM(budget) as total_budget FROM projects WHERE budget IS NOT NULL');
        $stats['total_budget'] = $stmt->fetchColumn() ?: 0;
        
        // Projekty w tym miesiącu
        $stmt = $this->pdo->query('SELECT COUNT(*) as count FROM projects WHERE MONTH(created_at) = MONTH(NOW()) AND YEAR(created_at) = YEAR(NOW())');
        $stats['this_month'] = $stmt->fetchColumn() ?: 0;
        
        return $stats;
    }
}
