<?php
// src/Pages/Dashboard.php

namespace Dev\ProjetoIntegrador\Pages;

use Dev\ProjetoIntegrador\Pages\Page;

require_once __DIR__ . '/../../autoload.php';

/**
 * Classe Dashboard
 *
 * Responsável por exibir a página de dashboard.
 *
 * @package Pages
 */
class Dashboard extends Page
{
    /**
     * @var string $title O título da página de dashboard.
     */
    protected string $title = "Dashboard | Projeto Saúde";

    /**
     * @var array $linksHead Os links a serem adicionados ao cabeçalho.
     */
    protected array $linksHead = [
        '<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=folder_shared" />'
    ];

    /**
     * Retorna o link para o arquivo CSS da página de dashboard.
     *
     * @return string O link para o arquivo CSS da página de dashboard.
     */
    protected function cssLink(): string
    {
        return '/public/css/dashboard.css';
    }

    /**
     * Construtor da classe Dashboard.
     *
     * Inicializa a página de dashboard e verifica se o usuário está autenticado.
     */
    public function __construct()
    {
        session_start();
        if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
            header('Location: /login');
            exit();
        }
        parent::__construct();
    }

    /**
     * Renderiza o conteúdo da página de dashboard.
     *
     * @return void
     */
    protected function renderContent(): void
    {
        echo <<<HTML
        <menu id="menu">
            <nav>
                <div style="margin-bottom: 5px;">
                    <h1 style="white-space: nowrap;">Gestão Hospitalar</h1>
                </div>
                <a href="/dashboard">
                    <i class="fa-solid fa-house-chimney"></i>
                    <p>Início</p>
                </a>
                <a href="/patients">
                    <i class="fa-solid fa-user-injured"></i>
                    <p>Pacientes</p>
                </a>
                <a href="/">
                    <i class="fa-solid fa-calendar-check"></i>
                    <p>Consultas</p>
                </a>
                <a href="">
                    <i class="fa-solid fa-file-medical"></i>
                    <p>Receitas</p>
                </a>
                <a href="">
                    <i class="fa-solid fa-flask-vial"></i>
                    <p>Exames</p>
                </a>
                <a href="">
                    <i class="fa-solid fa-syringe"></i>
                    <p>Vacinas</p>
                </a>
                <a href="">
                    <i class="fa-solid fa-pills"></i>
                    <p>Medicamentos</p>
                </a>
                <a href="">
                    <i class="fa-solid fa-file-signature"></i>
                    <p>Relatórios</p>
                </a>
                <a href="">
                    <i class="fa-solid fa-gear"></i>
                    <p>Configurações</p>
                </a>
            </nav>
            <a id="logout" href="/logout">
                <i class="fa-solid fa-right-from-bracket"></i>
                <p>Logout</p>
            </a>
        </menu>
        HTML;
    }

}
