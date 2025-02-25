<?php
// src/Controllers/UserController.php

namespace Dev\ProjetoIntegrador\Controllers;

use Dev\ProjetoIntegrador\Model\User;
use Dev\ProjetoIntegrador\Config\Database;

require_once __DIR__ . '/../../autoload.php';

/**
 * Classe UserController
 *
 * Responsável por gerenciar as operações relacionadas aos usuários.
 *
 * @package Controllers
 */
class UserController
{
    /**
     * Cria um novo usuário.
     *
     * @param string $username Nome de usuário.
     * @param string $email Email do usuário.
     * @param string $password Senha do usuário.
     * @return bool True se o usuário foi criado com sucesso, false caso contrário.
     */
    public function create(string $username, string $email, string $password): bool
    {
        $user = new User($username, $email, $password);

        $database = new Database();
        $db = $database->getConnection();

        $query = "INSERT INTO users (username, email, password) VALUES (:username, :email, :password)";
        $stmt = $db->prepare($query);

        $stmt->bindParam(':username', $user->getUsername());
        $stmt->bindParam(':email', $user->getEmail());
        $stmt->bindParam(':password', $user->getPassword());

        try {
            if ($stmt->execute()) {
                return true;
            }
        } catch (\PDOException $e) {
            error_log('Database error: ' . $e->getMessage());
        }

        return false;
    }

    /**
     * Autentica um usuário.
     *
     * @param string $username Nome de usuário.
     * @param string $password Senha do usuário.
     * @return bool True se a autenticação for bem-sucedida, false caso contrário.
     */
    public function authenticate(string $username, string $password): bool
    {
        $user = $this->getUser($username);
        if (!$user) {
            return false;
        }
        return password_verify($password, $user['password']);
    }

    /**
     * Faz logout de um usuário.
     */
    public function logout(): void
    {
        session_start();
        session_unset();
        session_destroy();
    }

    /**
     * Obtém os dados do usuário com base no nome de usuário fornecido.
     *
     * @param string $username Nome de usuário.
     * @return array|null Retorna um array com os dados do usuário ou null se o usuário não for encontrado.
     */
    private function getUser(string $username): ?array
    {
        $database = new Database();
        $db = $database->getConnection();
        $stmt = $db->prepare('SELECT * FROM users WHERE username = :username');
        $stmt->execute(['username' => $username]);
        $user = $stmt->fetch();
        return $user ?: null;
    }

}
