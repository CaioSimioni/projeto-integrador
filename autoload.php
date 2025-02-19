<?php
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

spl_autoload_register(function (string $nomeCompletoClasse) {

    $caminhoArquivo = str_replace('Dev\\CaioSimioni\\ProjetoSaude', 'src', $nomeCompletoClasse);
    $caminhoArquivo = str_replace('\\', DIRECTORY_SEPARATOR, $caminhoArquivo);
    $caminhoArquivo .= '.php';

    if (file_exists($caminhoArquivo)) {
        require_once($caminhoArquivo);
    }
});
