<?php
// src/Pages/Page404.php

namespace Dev\ProjetoIntegrador\Pages;

use Dev\ProjetoIntegrador\Pages\Page;

require_once __DIR__ . '/../../autoload.php';

class Page404 extends Page
{
    protected string $title = "Erro 404 - Página Não Encontrada";

    public function __construct()
    {
        parent::__construct();
    }

    protected function renderContent(): void
    {
        $pageUrl = $_SERVER['REQUEST_URI'] ?? 'desconhecida';
        ?>
        <h1>Erro 404</h1>
        <p>A página <strong><?php echo $pageUrl; ?></strong> não foi encontrada.</p>
        <a href="/">Voltar para a página inicial</a>
        <?php
    }

    protected function cssLink(): string
    {
        return '/public/css/404.css';
    }
}
