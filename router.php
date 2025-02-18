<?php
/**
 * Arquivo responsável por gerenciar as rotas da aplicação.
 *
 * Este arquivo define as rotas disponíveis e instancia a classe correspondente
 * com base na URL requisitada.
 *
 * @package Dev\CaioSimioni\ProjetoSaude
 * @author CaioSimioni
 */

use Dev\CaioSimioni\ProjetoSaude\Pages\Home;
use Dev\CaioSimioni\ProjetoSaude\Pages\Page404;

require_once './autoload.php';

// Obtém a URL requisitada
$request_uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Valida e sanitiza a URL requisitada
$request_uri = filter_var($request_uri, FILTER_SANITIZE_URL);

// Define as rotas disponíveis
$routes = [
    '/' => Home::class,
    '/home' => Home::class
];

// Se a rota existe, instancia a classe correspondente, senão exibe 404
if (array_key_exists($request_uri, $routes)) {
    $class = $routes[$request_uri];
    if (class_exists($class)) {
        new $class();
    } else {
        new Page404();
    }
} else {
    new Page404();
}
