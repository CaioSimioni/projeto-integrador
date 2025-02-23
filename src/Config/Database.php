<?php

namespace Dev\ProjetoIntegrador\Config;

use Dev\ProjetoIntegrador\Config\Env;
use PDO;
use PDOException;

require_once __DIR__ . '/../../autoload.php';

class Database
{
    private $host = '';
    private $name = '';
    private $driver = '';
    private $user = '';
    private $pass = '';
    private $conn;

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
