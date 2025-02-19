<?php
// src/Pages/Home.php

namespace Dev\CaioSimioni\ProjetoSaude\Pages;

use Dev\CaioSimioni\ProjetoSaude\Model\Page;

require_once __DIR__ . '/../../autoload.php';

class Home extends Page
{
    protected string $title = "Home | Projeto Saúde";

    protected function renderContent(): void
    {
        echo "<h1>Home</h1>";
        echo '<a href="/">Voltar para a página inicial</a>';
    }
}
