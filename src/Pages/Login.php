<?php
// src/Pages/Login.php

namespace Dev\ProjetoIntegrador\Pages;

use Dev\ProjetoIntegrador\Pages\Page;
use Dev\ProjetoIntegrador\Services\Authenticator;

require_once __DIR__ . '/../../autoload.php';

class Login extends Page
{
    protected string $title = "Login | Projeto Saúde";

    public function __construct()
    {
        session_start();
        if (isset($_SESSION['authenticated']) && $_SESSION['authenticated'] === true) {
            header('Location: dashboard');
            exit();
        }
        parent::__construct();
    }

    protected function renderContent(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario = $_POST['usuario'];
            $senha = $_POST['senha'];
            $authenticator = new Authenticator();
            if ($authenticator->authenticate($usuario, $senha)) {
                session_start();
                $_SESSION['authenticated'] = true;
                header('Location: dashboard');
                exit();
            } else {
                echo "Usuário ou senha inválidos.";
                return;
            }
        }
        ?>
        <div class="login-container">
            <div class="login-logo">
                <img src="http://localhost:8080/public/images/logo_circle.png" alt="Logo">
            </div>
            <div class="login-title">Bem-vindo ao Sistema</div>
            <form action="" method="post">
                <div class="mb-3">
                    <input type="text" class="form-control" placeholder="Usuário" name="usuario" required>
                </div>
                <div class="mb-3">
                    <input type="password" class="form-control" placeholder="Senha" name="senha" required>
                </div>
                <button type="submit" class="btn btn-primary">Entrar</button>
            </form>
        </div>
        <?php
    }

    protected function cssLink(): string
    {
        return 'http://localhost:8080/public/css/login.css';
    }
}
