<?php
// src/Pages/Page404.php

namespace Dev\ProjetoIntegrador\Pages;

use Dev\ProjetoIntegrador\Pages\Page;

require_once __DIR__ . '/../../autoload.php';

/**
 * Classe Page404
 *
 * Responsável por exibir a página de erro 404.
 *
 * @package Pages
 */
class Page404 extends Page
{
    /**
     * @var string $title O título da página 404.
     */
    protected string $title = "Erro 404 | Projeto Saúde";

    /**
     * Construtor da classe Page404.
     *
     * Inicializa a página 404.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Renderiza o conteúdo da página 404.
     *
     * @return void
     */
    protected function renderContent(): void
    {
        $pageUrl = $_SERVER['REQUEST_URI'] ?? 'desconhecida';
        ?>
        <h1>Erro 404</h1>
        <p>A página <strong><?php echo $pageUrl; ?></strong> não foi encontrada.</p>
        <a href="/">Voltar para a página inicial</a>
        <?php
    }

    /**
     * Retorna o link para o arquivo CSS da página 404.
     *
     * @return string O link para o arquivo CSS da página 404.
     */
    protected function cssLink(): string
    {
        return '/public/css/404.css';
    }
}
