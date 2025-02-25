<?php
// ProjetoIntegrador/server.php

/**
 * Este arquivo é responsável por gerenciar as rotas da aplicação.
 *
 * Ele define as rotas disponíveis e instancia a classe correspondente
 * com base na URL solicitada.
 *
 * @package Dev\ProjetoIntegrador
 */

use Dev\ProjetoIntegrador\Services\Router;

require_once './autoload.php';

// Obtém a URL requisitada
$request_uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Instancia o roteador e lida com a requisição
$router = new Router();
$router->handleRequest($request_uri);
