<?php
// src/Pages/Page.php

namespace Dev\ProjetoIntegrador\Pages;

use Dev\ProjetoIntegrador\Config\Env;

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
        ?>
        <!DOCTYPE html>
        <html lang="pt-BR">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title><?php echo $this->title ?></title>
            <link rel="shortcut icon" href="<?php echo Env::get('APP_URL')['APP_URL'] . '/public/favicon.ico' ?>"
                type="image/x-icon">
            <link rel="stylesheet" href="<?php echo Env::get('APP_URL')['APP_URL'] . '/public/css/style.css' ?>"
                type="text/css">
            <link rel="stylesheet" href="<?php echo $this->cssLink(); ?>" type="text/css">
        </head>

        <body>
            <?php
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

    /**
     * Retorna o link para o arquivo CSS da página.
     *
     * Este método deve ser implementado pelas subclasses para fornecer o link para o arquivo CSS específico da página.
     *
     * @return string O link para o arquivo CSS da página.
     */
    abstract protected function cssLink(): string;
}
