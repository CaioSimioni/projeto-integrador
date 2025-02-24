<?php

namespace Dev\ProjetoIntegrador\Pages;

use Dev\ProjetoIntegrador\Pages\Page;

require_once __DIR__ . '/../../autoload.php';

class Dashboard extends Page
{

    protected string $title = "Dashboard | Projeto Saúde";

    public function __construct()
    {
        session_start();
        if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
            header('Location: /login');
            exit();
        }
        parent::__construct();
    }

    protected function renderContent(): void
    {
        echo <<<HTML
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20,400,0,0" />
        <menu id="menu">
            <nav>

                <a href="">
                    <span class="material-symbols-rounded">
                    dashboard
                    </span>
                    <p>Dashboard</p>
                </a>
                <a href="">
                    <span class="material-symbols-rounded">
                    clinical_notes
                    </span>
                    <p>Consultas</p>
                </a>
                <a href="">
                    <span class="material-symbols-rounded">
                    assignment
                    </span>
                    <p>Prontuarios</p>
                </a>
                <a href="">
                    <span class="material-symbols-rounded">
                    shield_person
                    </span>
                    <p>Painel Admin</p>
                </a>
                <a href="/logout">
                    <span class="material-symbols-rounded">
                    logout
                    </span>
                    <p>Logout</p>
                </a>

            </nav>
        </menu>
        HTML;
    }

    protected function cssLink(): string
    {
        return '/public/css/dashboard.css';
    }
}
