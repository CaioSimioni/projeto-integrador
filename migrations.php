<?php
// ProjetoIntegrador/migrations.php

use Dev\ProjetoIntegrador\Config\Env;

require_once './autoload.php';

/**
 * Carrega as variáveis de ambiente do arquivo .env.
 */
Env::loadEnv();

/**
 * Obtém o driver do banco de dados a partir das variáveis de ambiente.
 */
$dbDriver = Env::get('DB_DRIVER')['DB_DRIVER'] ?? null;

echo "migrations script:\n";
echo "    driver: $dbDriver\n \n";

/**
 * Se o driver do banco de dados for MySQL, gera o script SQL para criar as tabelas
 * com base nas classes de modelo definidas em src/Model.
 */
if ($dbDriver === 'mysql') {
    $modelsPath = __DIR__ . '/src/Model';
    $initSqlPath = __DIR__ . '/init.sql';
    $logPath = __DIR__ . '/logs/migration.log';

    // Verifica se o diretório de modelos existe
    if (!is_dir($modelsPath)) {
        die("Erro: O diretório de modelos '$modelsPath' não existe.\n");
    }

    // Verifica se o diretório de logs existe, se não, cria-o
    if (!is_dir(dirname($logPath))) {
        mkdir(dirname($logPath), 0777, true);
    }

    $sqlStatements = [];
    $logEntries = [];
    $modelNames = [];

    /**
     * Itera sobre todos os arquivos de modelo em src/Model.
     */
    foreach (glob("$modelsPath/*.php") as $modelFile) {
        require_once $modelFile;
        $className = basename($modelFile, '.php');
        $fullClassName = "Dev\\ProjetoIntegrador\\Model\\$className";
        $modelNames[] = $className;

        // Exclude the base Model class
        if ($className === 'Model') {
            continue;
        }

        /**
         * Verifica se a classe do modelo existe e se é uma subclasse de Model.
         */
        if (class_exists($fullClassName) && is_subclass_of($fullClassName, 'Dev\\ProjetoIntegrador\\Model\\Model')) {
            $tableName = $fullClassName::tableName();
            $columns = $fullClassName::columns();

            $columnsSql = [];
            foreach ($columns as $name => $type) {
                $columnsSql[] = "$name $type";
            }

            /**
             * Adiciona a instrução SQL para criar a tabela.
             */
            $sqlStatements[] = "CREATE TABLE IF NOT EXISTS $tableName (\n    " . implode(",\n    ", $columnsSql) . "\n);";
            $logEntries[] = "$className - ok\n" . implode(",\n", $columnsSql) . ";";
            echo "$className - ok\n" . implode(",\n", $columnsSql) . ";\n\n";
        } else {
            $logEntries[] = "$className - fail\n[Erro: Classe $fullClassName não encontrada ou não é um subclasse de Model]";
            echo "$className - fail\n[Erro: Classe $fullClassName não encontrada ou não é um subclasse de Model]\n\n";
        }
    }

    echo "models: " . implode(", ", $modelNames) . "\n\n";

    /**
     * Escreve as instruções SQL no arquivo init.sql.
     */
    file_put_contents($initSqlPath, implode("\n\n", $sqlStatements));

    /**
     * Escreve o log no arquivo migration.log.
     */
    file_put_contents($logPath, implode("\n\n", $logEntries));
}
