<?php

namespace Dev\ProjetoIntegrador\Services;

use Dev\ProjetoIntegrador\Config\Database;

require_once __DIR__ . '/../../autoload.php';

class Authenticator
{
    public function authenticate(string $username, string $password): bool
    {
        $user = $this->getUser($username);
        if (!$user) {
            return false;
        }
        return password_verify($password, $user['password']);
    }

    public function logout(): void
    {
        session_start();
        session_unset();
        session_destroy();
    }

    private function getUser(string $username): ?array
    {
        $database = new Database();
        $db = $database->getConnection();
        $stmt = $db->prepare('SELECT * FROM users WHERE username = :username');
        $stmt->execute(['username' => $username]);
        return $stmt->fetch();
    }
}
