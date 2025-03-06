<?php
// ProjetoIntegrador/autoload.php

/**
 * Autoload de classes para o projeto.
 *
 * Este script registra uma função de autoload que carrega automaticamente
 * as classes do projeto quando elas são instanciadas. A função converte o
 * namespace completo da classe em um caminho de arquivo correspondente e
 * inclui o arquivo se ele existir.
 *
 * @param string $nomeCompletoClasse O nome completo da classe com namespace.
 *
 * @return void
 */

function namespaceToPath(string $namespace, array $mappings): string {
    foreach ($mappings as $prefix => $baseDir) {
        if (strpos($namespace, $prefix) === 0) {
            $relativeClass = substr($namespace, strlen($prefix));
            $relativeClass = str_replace('\\', DIRECTORY_SEPARATOR, $relativeClass);
            return $baseDir . DIRECTORY_SEPARATOR . $relativeClass . '.php';
        }
    }
    return '';
}

spl_autoload_register(function (string $nomeCompletoClasse) {
    $mappings = [
        'Dev\\ProjetoIntegrador\\Test' => 'test',
        'Dev\\ProjetoIntegrador' => 'src'
    ];

    $caminhoArquivo = namespaceToPath($nomeCompletoClasse, $mappings);

    if ($caminhoArquivo && file_exists($caminhoArquivo)) {
        require_once($caminhoArquivo);
    }
});
