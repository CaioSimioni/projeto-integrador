<?php
// src/Pages/Page.php

namespace Dev\ProjetoIntegrador\Pages;

require_once __DIR__ . '/../../autoload.php';

/**
 * Classe Page
 *
 * Esta classe abstrata representa a estrutura básica de uma página web com um cabeçalho, conteúdo e rodapé.
 * Ela fornece métodos para renderizar o cabeçalho e o rodapé, e exige que as subclasses implementem o método renderContent.
 *
 * @package Pages
 */
abstract class Page
{
    /**
     * @var string $title O título da página web.
     */
    protected string $title;

    /**
     * @var array $linksHead Os links a serem adicionados ao cabeçalho.
     */
    protected array $linksHead = [];

    /**
     * Construtor da página.
     *
     * Inicializa a página renderizando o cabeçalho, conteúdo e rodapé.
     */
    public function __construct()
    {
        $this->renderHeader();
        echo '<div class="container">';
        $this->renderContent();
        echo '</div>';
        $this->renderFooter();
    }

    /**
     * Renderiza o cabeçalho da página web.
     *
     * Exibe o HTML para o cabeçalho, incluindo a declaração DOCTYPE, meta tags, título e estilos básicos.
     *
     * @return void
     */
    protected function renderHeader(): void
    {
        echo '<!DOCTYPE html>
        <html lang="pt-BR">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">';
            echo '<title>' . $this->title . '</title>';
            echo '<link rel="shortcut icon" href="/public/favicon.ico" type="image/x-icon">
            <link rel="stylesheet" href="/public/css/style.css" type="text/css">
            <link rel="stylesheet" href="' . $this->cssLink() . '" type="text/css">';
            echo $this->addLinksHead();
            echo '<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
            <script src="https://kit.fontawesome.com/5fa94fcf2f.js" crossorigin="anonymous"></script>
        </head>
        <body>';
    }

    /**
     * Adiciona elementos de link ao cabeçalho.
     *
     * Este método usa a propriedade $linksHead para adicionar os links ao cabeçalho.
     *
     * @return string Retorna uma string contendo os elementos <link> concatenados com uma quebra de linha.
     */
    protected function addLinksHead(): string
    {
        $output = '';
        foreach ($this->linksHead as $element) {
            if (is_string($element) && stripos($element, '<link') !== false) {
                $output .= $element . PHP_EOL;
            }
        }
        return $output;
    }

    /**
     * Renderiza o rodapé da página web.
     *
     * Exibe as tags de fechamento para os elementos body e html.
     *
     * @return void
     */
    protected function renderFooter(): void
    {
        echo '<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script></body></html>';
    }

    /**
     * Renderiza o conteúdo da página web.
     *
     * Este método deve ser implementado pelas subclasses para definir o conteúdo específico da página.
     *
     * @return void
     */
    abstract protected function renderContent(): void;

    /**
     * Retorna o link para o arquivo CSS da página.
     *
     * Este método deve ser implementado pelas subclasses para fornecer o link para o arquivo CSS específico da página.
     *
     * @return string O link para o arquivo CSS da página.
     */
    abstract protected function cssLink(): string;
}
