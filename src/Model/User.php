<?php
// src/Model/User.php

namespace Dev\ProjetoIntegrador\Model;

use Dev\ProjetoIntegrador\Config\Database;
use PDO;

require_once __DIR__ . '/../../autoload.php';

class User
{
    private $username;
    private $email;
    private $password;

    public function __construct($username, $email, $password)
    {
        $this->setUsername($username);
        $this->setEmail($email);
        $this->setPassword($password);
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function setUsername($username)
    {
        if (!filter_var($username, FILTER_SANITIZE_STRING)) {
            throw new \InvalidArgumentException('Invalid username');
        }
        $this->username = $username;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException('Invalid email');
        }
        $this->email = $email;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        if (strlen($password) < 8) {
            throw new \InvalidArgumentException('Password must be at least 8 characters long');
        }
        $this->password = password_hash($password, PASSWORD_BCRYPT);
    }

    public function save()
    {
        $database = new Database();
        $db = $database->getConnection();

        $query = "INSERT INTO users (username, email, password) VALUES (:username, :email, :password)";
        $stmt = $db->prepare($query);

        $stmt->bindParam(':username', $this->username);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':password', $this->password);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }
}
