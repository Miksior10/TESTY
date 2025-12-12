<?php declare(strict_types=1);

namespace App\Models;

use PDO;

final class WorkTime {
    public function __construct(private PDO $pdo) {}

    public function getByUser(int $userId, ?string $date = null): array {
        $sql = 'SELECT * FROM work_time WHERE user_id = ?';
        $params = [$userId];
        
        if ($date) {
            $sql .= ' AND date = ?';
            $params[] = $date;
        }
        
        $sql .= ' ORDER BY date DESC';
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll() ?: [];
    }

    public function getToday(int $userId): ?array {
        $stmt = $this->pdo->prepare('SELECT * FROM work_time WHERE user_id = ? AND date = CURDATE()');
        $stmt->execute([$userId]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    public function clockIn(int $userId, string $time): bool {
        $stmt = $this->pdo->prepare('
            INSERT INTO work_time (user_id, date, start_time) 
            VALUES (?, CURDATE(), ?)
            ON DUPLICATE KEY UPDATE start_time = VALUES(start_time)
        ');
        return $stmt->execute([$userId, $time]);
    }

    public function clockOut(int $userId, string $time, ?int $breakDuration = null): bool {
        $stmt = $this->pdo->prepare('
            UPDATE work_time 
            SET end_time = ?, break_duration = COALESCE(?, break_duration),
                total_hours = (TIME_TO_SEC(?) - TIME_TO_SEC(start_time)) / 3600.0 - COALESCE(?, 0) / 60.0
            WHERE user_id = ? AND date = CURDATE() AND start_time IS NOT NULL
        ');
        return $stmt->execute([$time, $breakDuration, $time, $breakDuration, $userId]);
    }

    public function getMonthlyStats(int $userId, string $month): array {
        $stmt = $this->pdo->prepare('
            SELECT 
                COUNT(*) as days_worked,
                SUM(total_hours) as total_hours,
                AVG(total_hours) as avg_hours_per_day
            FROM work_time 
            WHERE user_id = ? AND DATE_FORMAT(date, "%Y-%m") = ?
        ');
        $stmt->execute([$userId, $month]);
        return $stmt->fetch() ?: [];
    }
}