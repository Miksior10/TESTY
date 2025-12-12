<?php declare(strict_types=1);

namespace App\Models;

use PDO;

final class Admin {
    public function __construct(private PDO $pdo) {}

    public function findByUsername(string $username): ?array {
        $stmt = $this->pdo->prepare('SELECT * FROM admins WHERE username = ?');
        $stmt->execute([$username]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    public function updateLastLogin(int $adminId): void {
        $stmt = $this->pdo->prepare('UPDATE admins SET last_login = NOW() WHERE id = ?');
        $stmt->execute([$adminId]);
    }

    public function verifyPassword(string $password, string $hash): bool {
        return password_verify($password, $hash);
    }
}
