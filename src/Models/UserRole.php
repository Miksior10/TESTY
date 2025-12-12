<?php declare(strict_types=1);

namespace App\Models;

use PDO;

final class UserRole {
    public function __construct(private PDO $pdo) {}

    public function getUserWithRole(int $userId): ?array {
        $stmt = $this->pdo->prepare('
            SELECT u.*, ur.role, ur.permissions, ur.assigned_at, a.username as assigned_by_name
            FROM users u
            LEFT JOIN user_roles ur ON u.id = ur.user_id
            LEFT JOIN admins a ON ur.assigned_by = a.id
            WHERE u.id = ?
            ORDER BY ur.assigned_at DESC
            LIMIT 1
        ');
        $stmt->execute([$userId]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    public function getAllUsersWithRoles(): array {
        $stmt = $this->pdo->query('
            SELECT u.*, ur.role, ur.permissions, ur.assigned_at, a.username as assigned_by_name
            FROM users u
            LEFT JOIN user_roles ur ON u.id = ur.user_id
            LEFT JOIN admins a ON ur.assigned_by = a.id
            ORDER BY u.created_at DESC
        ');
        return $stmt->fetchAll() ?: [];
    }

    public function assignRole(int $userId, string $role, int $assignedBy, ?array $permissions = null): bool {
        // Usuń poprzednią rolę
        $stmt = $this->pdo->prepare('DELETE FROM user_roles WHERE user_id = ?');
        $stmt->execute([$userId]);
        
        // Dodaj nową rolę
        $stmt = $this->pdo->prepare('
            INSERT INTO user_roles (user_id, role, permissions, assigned_by) 
            VALUES (?, ?, ?, ?)
        ');
        return $stmt->execute([
            $userId,
            $role,
            $permissions ? json_encode($permissions) : null,
            $assignedBy
        ]);
    }

    public function getRoleStats(): array {
        $stmt = $this->pdo->query('
            SELECT 
                COALESCE(ur.role, "user") as role,
                COUNT(*) as count
            FROM users u
            LEFT JOIN user_roles ur ON u.id = ur.user_id
            GROUP BY COALESCE(ur.role, "user")
        ');
        return $stmt->fetchAll() ?: [];
    }
}
