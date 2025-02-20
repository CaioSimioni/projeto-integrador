<?php
// src/Tests/TestConnectionDB.php

namespace Dev\CaioSimioni\ProjetoSaude\Tests;

use Dev\CaioSimioni\ProjetoSaude\Config\Env;

require_once __DIR__ . '/../../autoload.php';

/**
 * Classe TestConnectionDB
 *
 * Esta classe é responsável por estabelecer uma conexão com o banco de dados
 * utilizando as variáveis de ambiente carregadas pela classe Env.
 *
 * @package Tests
 */
class TestConnectionDB
{
    /**
     * @var \PDO $pdo Instância da classe PDO para a conexão com o banco de dados
     */
    private $pdo;

    /**
     * Construtor da classe TestConnectionDB.
     * Estabelece a conexão com o banco de dados utilizando as variáveis de ambiente.
     */
    public function __construct()
    {
        $env = new Env();
        $dbValues = $env->get('DB_HOST', 'DB_DRIVER', 'DB_DATABASE', 'DB_USER', 'DB_PASSWORD');

        $dsn = "{$dbValues['DB_DRIVER']}:host={$dbValues['DB_HOST']};dbname={$dbValues['DB_DATABASE']}";
        print_r($dbValues);
        echo $dsn . PHP_EOL;

        try {
            $this->pdo = new \PDO($dsn, $dbValues['DB_USER'], $dbValues['DB_PASSWORD']);
            echo "Conexão bem-sucedida!" . PHP_EOL;
        } catch (\PDOException $e) {
            echo "Erro na conexão: " . $e->getMessage() . PHP_EOL;
        }
    }
}
