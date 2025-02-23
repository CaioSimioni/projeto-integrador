<?php
// src/Config/Env.php

namespace Dev\ProjetoIntegrador\Config;

/**
 * Classe Env
 *
 * Esta classe é responsável por carregar e fornecer acesso às variáveis de ambiente
 * definidas no arquivo .env localizado na raiz do projeto.
 *
 * @package Config
 */
class Env
{
    /**
     * @var array $variables Armazena as variáveis de ambiente carregadas do arquivo .env
     */
    private static $variables = [];

    /**
     * Carrega as variáveis de ambiente do arquivo .env.
     *
     * @throws \Exception Se o arquivo .env não for encontrado ou se houver um erro de formatação.
     */
    public static function loadEnv()
    {
        if (!empty(self::$variables)) {
            return;
        }

        $envFile = __DIR__ . '/../../.env';
        if (!file_exists($envFile)) {
            throw new \Exception('.env file not found');
        }

        $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            if (strpos(trim($line), '#') === 0) {
                continue;
            }

            if (strpos($line, '=') === false) {
                throw new \Exception('Invalid .env file format');
            }

            [$name, $value] = explode('=', $line, 2);
            $name = trim($name);
            $value = trim($value);

            if (empty($name)) {
                throw new \Exception('Invalid environment variable name');
            }

            self::$variables[$name] = $value;
        }
    }

    /**
     * Retorna o valor das variáveis de ambiente solicitadas.
     *
     * @param string ...$keys Nomes das variáveis de ambiente a serem retornadas.
     * @return array Um array associativo contendo os valores das variáveis solicitadas.
     */
    public static function get(...$keys)
    {
        self::loadEnv();

        if (empty($keys)) {
            return self::$variables;
        }

        $result = [];
        foreach ($keys as $key) {
            $result[$key] = self::$variables[$key] ?? null;
        }

        return $result;
    }
}
