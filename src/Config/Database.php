<?php
// src/Config/Database.php

namespace Dev\ProjetoIntegrador\Config;

use Dev\ProjetoIntegrador\Config\Env;
use PDO;
use PDOException;

require_once __DIR__ . '/../../autoload.php';

/**
 * Classe Database
 *
 * Responsável por gerenciar a conexão com o banco de dados.
 *
 * @package Config
 */
class Database
{
    /**
     * @var string $host O host do banco de dados.
     */
    private $host = '';

    /**
     * @var string $name O nome do banco de dados.
     */
    private $name = '';

    /**
     * @var string $driver O driver do banco de dados.
     */
    private $driver = '';

    /**
     * @var string $user O usuário do banco de dados.
     */
    private $user = '';

    /**
     * @var string $pass A senha do banco de dados.
     */
    private $pass = '';

    /**
     * @var PDO $conn A conexão com o banco de dados.
     */
    private $conn;

    /**
     * Construtor da classe Database.
     *
     * Inicializa os valores de conexão com o banco de dados a partir do arquivo de ambiente.
     */
    public function __construct()
    {
        $env = new Env();
        $dbValues = $env->get('DB_HOST', 'DB_DRIVER', 'DB_DATABASE', 'DB_USER', 'DB_PASSWORD');
        $this->host = $dbValues['DB_HOST'];
        $this->driver = $dbValues['DB_DRIVER'];
        $this->name = $dbValues['DB_DATABASE'];
        $this->user = $dbValues['DB_USER'];
        $this->pass = $dbValues['DB_PASSWORD'];
    }

    /**
     * Obtém a conexão com o banco de dados.
     *
     * @return PDO A conexão com o banco de dados.
     * @throws \Exception Se ocorrer um erro ao conectar ao banco de dados.
     */
    public function getConnection()
    {
        $this->conn = null;

        try {
            $this->conn = new PDO($this->driver . ":host=" . $this->host . ";dbname=" . $this->name, $this->user, $this->pass);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        } catch (PDOException $exception) {
            error_log("Connection error: " . $exception->getMessage());
            throw new \Exception("Database connection error");
        }

        return $this->conn;
    }
}
