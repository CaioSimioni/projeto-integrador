<?php
// src/Services/Router.php

namespace Dev\ProjetoIntegrador\Services;

use Dev\ProjetoIntegrador\Pages\Login;
use Dev\ProjetoIntegrador\Pages\Page404;
use Dev\ProjetoIntegrador\Pages\Dashboard;
use Dev\ProjetoIntegrador\Controllers\UserController;

require_once __DIR__ . '/../../autoload.php';

/**
 * Classe Router
 *
 * Responsável por gerenciar as rotas da aplicação.
 *
 * @package Services
 */
class Router
{
    /**
     * @var array $routes
     * Array de rotas da aplicação, mapeando URIs para funções.
     */
    private array $routes = [
        '/' => 'login',
        '/login' => 'login',
        '/dashboard' => 'dashboard',
        '/logout' => 'logout'
    ];

    /**
     * Manipula a requisição recebida e direciona para a função correspondente.
     *
     * @param string $requestUri URI da requisição.
     */
    public function handleRequest(string $requestUri): void
    {
        $requestUri = filter_var($requestUri, FILTER_SANITIZE_URL);

        // Verifica se a requisição é para um arquivo no diretório público
        if (strpos($requestUri, '/public/') === 0) {

            // Proteção contra Directory Traversal
            $realPath = realpath(__DIR__ . '/../../' . $requestUri);
            if ($realPath === false || strpos($realPath, realpath(__DIR__ . '/../../public')) !== 0) {
                new Page404();
                return;
            }

            if (is_file($realPath)) {
                $this->servePublicFile($realPath);
                return;
            }
        }

        if (array_key_exists($requestUri, $this->routes)) {
            $function = $this->routes[$requestUri];
            $this->$function();
        } else {
            new Page404();
        }
    }

    /**
     * Exibe a página de login.
     */
    private function login(): void
    {
        new Login();
    }

    /**
     * Exibe a página de dashboard.
     */
    private function dashboard(): void
    {
        new Dashboard();
    }

    /**
     * Realiza o logout do usuário e redireciona para a página de login.
     */
    private function logout(): void
    {
        $userController = new UserController();
        $userController->logout();
        header_remove();
        header('Location: /login');
        exit();
    }

    /**
     * Serve um arquivo público.
     *
     * @param string $filePath Caminho do arquivo a ser servido.
     */
    private function servePublicFile(string $filePath): void
    {
        if (!file_exists($filePath)) {
            new Page404();
            return;
        }

        $mimeType = mime_content_type($filePath);

        // Corrigir o tipo MIME para arquivos CSS
        if (pathinfo($filePath, PATHINFO_EXTENSION) === 'css') {
            $mimeType = 'text/css';
        }

        header_remove();
        header('Content-Type: ' . $mimeType);
        readfile($filePath);
    }
}
