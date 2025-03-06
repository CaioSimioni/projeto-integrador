<?php
// ProjetoIntegrador/tests.php

/**
 * Este arquivo é responsável por executar os testes unitários.
 *
 * Ele instancia as classes de teste e executa os métodos de teste correspondentes.
 */


use Dev\ProjetoIntegrador\Test\UserTest;

require_once __DIR__ . '/autoload.php';

$testClasses = [
    UserTest::class,
];

function rodarTestes($testClasses)
{
    foreach ($testClasses as $testClass) {
        $testInstance = new $testClass();
        $methods = get_class_methods($testInstance);
        foreach ($methods as $method) {
            if (strpos($method, 'test') === 0) {
                echo "Executando {$testClass}::{$method}... ";
                try {
                    $testInstance->$method();
                    echo "✔️ Sucesso\n";
                } catch (Exception $e) {
                    echo "❌ Falha: " . $e->getMessage() . "\n";
                }
            }
        }
    }
}

rodarTestes($testClasses);
