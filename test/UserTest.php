<?php

namespace Dev\ProjetoIntegrador\Test;

use Dev\ProjetoIntegrador\Model\User;

use InvalidArgumentException;

require_once __DIR__ . '/../autoload.php';

class UserTest
{
    public function testUserCreation()
    {
        $user = new User('testuser', 'test@example.com', 'password123');
        if ($user->getUsername() !== 'testuser') {
            throw new \Exception('Username should be testuser');
        }
        if ($user->getEmail() !== 'test@example.com') {
            throw new \Exception('Email should be test@example.com');
        }
        if (!password_verify('password123', $user->getPassword())) {
            throw new \Exception('Password should be hashed correctly');
        }
    }

    public function testInvalidUsername()
    {
        try {
            new User('', 'test@example.com', 'password123');
            throw new \Exception('Exception should be thrown for invalid username');
        } catch (InvalidArgumentException $e) {
            if ($e->getMessage() !== 'Invalid username') {
                throw new \Exception('Exception message should be "Invalid username"');
            }
        }
    }

    public function testInvalidEmail()
    {
        try {
            new User('testuser', 'invalid-email', 'password123');
            throw new \Exception('Exception should be thrown for invalid email');
        } catch (InvalidArgumentException $e) {
            if ($e->getMessage() !== 'Invalid email') {
                throw new \Exception('Exception message should be "Invalid email"');
            }
        }
    }

    public function testShortPassword()
    {
        try {
            new User('testuser', 'test@example.com', 'short');
            throw new \Exception('Exception should be thrown for short password');
        } catch (InvalidArgumentException $e) {
            if ($e->getMessage() !== 'Password must be at least 8 characters long') {
                throw new \Exception('Exception message should be "Password must be at least 8 characters long"');
            }
        }
    }
}
