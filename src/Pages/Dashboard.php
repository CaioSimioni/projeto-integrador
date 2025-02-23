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
        <h1>Dashboard</h1>
        <p>Welcome to the dashboard!</p>
        HTML;
    }

    protected function cssLink(): string
    {
        return '/public/css/dashboard.css';
    }
}
