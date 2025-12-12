<?php declare(strict_types=1);
// src/Controllers/Home/HomeController.php

namespace App\Controllers\Home;

use App\Models\Database;
use App\Models\User;

final class HomeController {
    public function __construct(private array $config) {}

    public function index(): void {
        $pdo = Database::getConnection($this->config['db']);
        $userModel = new User($pdo);
        $users = $userModel->getAll();

        $title = 'Witaj w PHP + JS';
        require __DIR__ . '/../../Views/home.php';
    }
}


