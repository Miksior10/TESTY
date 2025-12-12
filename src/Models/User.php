<?php
// src/Models/User.php
declare(strict_types=1);

namespace App\Models;

use PDO;

final class User {
    public function __construct(private PDO $pdo) {}

    public function getAll(): array {
        $stmt = $this->pdo->query('SELECT id, name, email, department, position, hire_date, salary, is_active, created_at FROM users ORDER BY id DESC');
        return $stmt->fetchAll() ?: [];
    }

    public function getById(int $id): ?array {
        $stmt = $this->pdo->prepare('SELECT * FROM users WHERE id = ?');
        $stmt->execute([$id]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    public function findByEmail(string $email): ?array {
        $stmt = $this->pdo->prepare('SELECT * FROM users WHERE email = ?');
        $stmt->execute([$email]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    public function create(array $data): int {
        $stmt = $this->pdo->prepare('
            INSERT INTO users (name, email, password_hash, department, position, hire_date, salary, is_active) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ');
        $stmt->execute([
            $data['name'],
            $data['email'],
            $data['password_hash'] ?? null,
            $data['department'] ?? null,
            $data['position'] ?? null,
            $data['hire_date'] ?? null,
            $data['salary'] ?? null,
            $data['is_active'] ?? true
        ]);
        return (int) $this->pdo->lastInsertId();
    }

    public function update(int $id, array $data): bool {
        $fields = [];
        $values = [];
        
        foreach ($data as $key => $value) {
            $fields[] = "$key = ?";
            $values[] = $value;
        }
        
        $values[] = $id;
        $sql = 'UPDATE users SET ' . implode(', ', $fields) . ' WHERE id = ?';
        
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($values);
    }

    public function getStats(): array {
        $stats = [];
        
        // Liczba użytkowników
        $stmt = $this->pdo->query('SELECT COUNT(*) as total FROM users');
        $stats['total_users'] = $stmt->fetchColumn() ?: 0;
        
        // Aktywni użytkownicy
        $stmt = $this->pdo->query('SELECT COUNT(*) as active FROM users WHERE is_active = 1');
        $stats['active_users'] = $stmt->fetchColumn() ?: 0;
        
        // Według działów
        $stmt = $this->pdo->query('SELECT department, COUNT(*) as count FROM users WHERE department IS NOT NULL GROUP BY department');
        $stats['by_department'] = $stmt->fetchAll() ?: [];
        
        return $stats;
    }
}