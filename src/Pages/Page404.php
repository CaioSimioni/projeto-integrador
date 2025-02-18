<?php
// src/Pages/Page404.php

namespace Dev\CaioSimioni\ProjetoSaude\Pages;

use Dev\CaioSimioni\ProjetoSaude\Model\Page;

require_once __DIR__ . '/../../autoload.php';

class Page404 extends Page
{
    protected string $title = "Erro 404 - Página Não Encontrada";

    protected function renderContent(): void
    {
        $pagina = $_SERVER['REQUEST_URI'] ?? 'desconhecida';
        echo "<h1>Erro 404</h1>";
        echo "<p>A página <strong>$pagina</strong> não foi encontrada.</p>";
        echo '<a href="/">Voltar para a página inicial</a>';
    }
}
