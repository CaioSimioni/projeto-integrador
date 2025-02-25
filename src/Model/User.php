<?php
// src/Model/User.php

namespace Dev\ProjetoIntegrador\Model;

use Dev\ProjetoIntegrador\Model\Model;

require_once __DIR__ . '/../../autoload.php';

/**
 * Classe User
 *
 * Representa um usuário no sistema.
 *
 * @package Model
 */
class User extends Model
{
    /**
     * @var string $username
     * Nome de usuário.
     */
    private $username;

    /**
     * @var string $email
     * Email do usuário.
     */
    private $email;

    /**
     * @var string $password
     * Senha do usuário.
     */
    private $password;

    /**
     * Construtor da classe User.
     *
     * @param string $username Nome de usuário.
     * @param string $email Email do usuário.
     * @param string $password Senha do usuário.
     */
    public function __construct($username, $email, $password)
    {
        $this->setUsername($username);
        $this->setEmail($email);
        $this->setPassword($password);
    }

    /**
     * Obtém o nome de usuário.
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Define o nome de usuário.
     *
     * @param string $username Nome de usuário.
     * @throws \InvalidArgumentException Se o nome de usuário for inválido.
     */
    public function setUsername($username)
    {
        $username = htmlspecialchars($username, ENT_QUOTES, 'UTF-8');
        if (empty($username)) {
            throw new \InvalidArgumentException('Invalid username');
        }
        $this->username = $username;
    }

    /**
     * Obtém o email do usuário.
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Define o email do usuário.
     *
     * @param string $email Email do usuário.
     * @throws \InvalidArgumentException Se o email for inválido.
     */
    public function setEmail($email)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException('Invalid email');
        }
        $this->email = $email;
    }

    /**
     * Obtém a senha do usuário.
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Define a senha do usuário.
     *
     * @param string $password Senha do usuário.
     * @throws \InvalidArgumentException Se a senha for menor que 8 caracteres.
     */
    public function setPassword($password)
    {
        if (strlen($password) < 8) {
            throw new \InvalidArgumentException('Password must be at least 8 characters long');
        }
        $this->password = password_hash($password, PASSWORD_ARGON2ID);
    }

    /**
     * Retorna o nome da tabela associada ao modelo.
     *
     * @return string
     */
    public static function tableName(): string
    {
        return 'users';
    }

    /**
     * Retorna as colunas da tabela associada ao modelo.
     *
     * @return array
     */
    public static function columns(): array
    {
        return [
            'id' => 'INT AUTO_INCREMENT PRIMARY KEY',
            'username' => 'VARCHAR(50) NOT NULL',
            'email' => 'VARCHAR(100) NOT NULL',
            'password' => 'VARCHAR(255) NOT NULL',
            'created_at' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP'
        ];
    }
}

