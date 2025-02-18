<?php
// src/Model/Page.php

namespace Dev\CaioSimioni\ProjetoSaude\Model;

/**
 * Classe Page
 *
 * Esta classe abstrata representa a estrutura básica de uma página web com um cabeçalho, conteúdo e rodapé.
 * Ela fornece métodos para renderizar o cabeçalho e o rodapé, e exige que as subclasses implementem o método renderContent.
 *
 * @package Model
 */
abstract class Page
{
    /**
     * @var string $title O título da página web.
     */
    protected string $title = "ProjetoSaude";

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
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>' . $this->title . '</title>
            <style>
                body { font-family: Arial, sans-serif; text-align: center; padding: 50px; }
                .container { max-width: 600px; margin: auto; }
            </style>
        </head>
        <body>';
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
        echo '</body></html>';
    }

    /**
     * Renderiza o conteúdo da página web.
     *
     * Este método deve ser implementado pelas subclasses para definir o conteúdo específico da página.
     *
     * @return void
     */
    abstract protected function renderContent(): void;
}
