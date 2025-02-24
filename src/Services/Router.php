<?php
namespace Dev\ProjetoIntegrador\Services;

use Dev\ProjetoIntegrador\Pages\Login;
use Dev\ProjetoIntegrador\Pages\Page404;
use Dev\ProjetoIntegrador\Pages\Dashboard;
use Dev\ProjetoIntegrador\Services\Authenticator;

require_once __DIR__ . '/../../autoload.php';

class Router
{
    private array $pageRoutes = [
        '/' => Login::class,
        '/login' => Login::class,
        '/dashboard' => Dashboard::class,
    ];

    private array $functionRoutes = [
        '/logout' => 'logout'
    ];

    public function handleRequest(string $requestUri): void
    {
        $requestUri = filter_var($requestUri, FILTER_SANITIZE_URL);

        // Check if the request is for a file in the public directory
        if (strpos($requestUri, '/public/') === 0) {
            $publicFilePath = realpath(__DIR__ . '/../../' . $requestUri);
            if ($publicFilePath && strpos($publicFilePath, realpath(__DIR__ . '/../../public')) === 0 && is_file($publicFilePath)) {
                $this->servePublicFile($publicFilePath);
                return;
            }
        }

        if (array_key_exists($requestUri, $this->pageRoutes)) {
            $class = $this->pageRoutes[$requestUri];
            if (class_exists($class)) {
                new $class();
            } else {
                new Page404();
            }
        } elseif (array_key_exists($requestUri, $this->functionRoutes)) {
            $function = $this->functionRoutes[$requestUri];
            $this->$function();
        } else {
            new Page404();
        }
    }

    private function logout(): void
    {
        $authenticator = new Authenticator();
        $authenticator->logout();
        header('Location: /login');
        exit();
    }

    private function servePublicFile(string $filePath): void
    {
        $mimeType = mime_content_type($filePath);

        // Corrigir o tipo MIME para arquivos CSS
        if (pathinfo($filePath, PATHINFO_EXTENSION) === 'css') {
            $mimeType = 'text/css';
        }

        header('Content-Type: ' . $mimeType);
        readfile($filePath);
    }
}
