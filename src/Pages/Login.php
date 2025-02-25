<?php
// src/Pages/Login.php

namespace Dev\ProjetoIntegrador\Pages;

use Dev\ProjetoIntegrador\Pages\Page;
use Dev\ProjetoIntegrador\Controllers\UserController;

require_once __DIR__ . '/../../autoload.php';

/**
 * Classe Login
 *
 * Responsável por exibir e processar a página de login.
 *
 * @package Pages
 */
class Login extends Page
{
    /**
     * @var string $title O título da página de login.
     */
    protected string $title = "Login | Projeto Saúde";

    /**
     * Construtor da classe Login.
     *
     * Inicializa a página de login e verifica se o usuário já está autenticado.
     */
    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (isset($_SESSION['authenticated']) && $_SESSION['authenticated'] === true) {
            header('Location: dashboard');
            exit();
        }
        parent::__construct();
    }

    /**
     * Renderiza o conteúdo da página de login.
     *
     * @return void
     */
    protected function renderContent(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->processLoginForm();
        }
        ?>
        <div x-data="{ errorMessage: '<?php echo $_SESSION['error'] ?? ''; ?>' }" class="login-container">
            <div class="login-logo">
                <img src="/public/images/logo_circle.png" alt="Logo">
            </div>
            <div class="login-title">Bem-vindo ao Sistema</div>
            <form action="" method="post">
                <div class="mb-3">
                    <input type="text" class="form-control" placeholder="Usuário" name="usuario" required>
                </div>
                <div class="mb-3">
                    <input type="password" class="form-control" placeholder="Senha" name="senha" required>
                </div>
                <div class="error-message" x-show="errorMessage" class="error-message" x-text="errorMessage"></div>
                <button type="submit" class="btn btn-primary">Entrar</button>
            </form>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php
    }

    /**
     * Processa o formulário de login.
     *
     * @return void
     */
    private function processLoginForm(): void
    {
        $usuario = $_POST['usuario'];
        $senha = $_POST['senha'];
        $userController = new UserController();

        if ($userController->authenticate($usuario, $senha)) {
            session_start();
            $_SESSION['authenticated'] = true;
            unset($_SESSION['error']);
            header('Location: dashboard');
            exit();
        } else {
            session_start();
            $_SESSION['error'] = 'Usuário ou senha inválidos.';
            header('Location: ' . $_SERVER['REQUEST_URI']);
            exit();
        }
    }

    /**
     * Retorna o link para o arquivo CSS da página de login.
     *
     * @return string O link para o arquivo CSS da página de login.
     */
    protected function cssLink(): string
    {
        return '/public/css/login.css';
    }
}
