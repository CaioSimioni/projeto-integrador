<?php
// src/Model/Model.php

namespace Dev\ProjetoIntegrador\Model;

/**
 * Classe Model
 *
 * Uma classe base para todos os modelos na aplicação. Esta classe fornece
 * funcionalidades e propriedades comuns que podem ser compartilhadas entre
 * diferentes modelos. Normalmente, inclui métodos para interagir com o banco
 * de dados, como consultar, inserir, atualizar e deletar registros.
 *
 * As propriedades e métodos desta classe devem ser projetados para serem
 * reutilizáveis e extensíveis por subclasses que representam entidades
 * específicas na aplicação.
 *
 * Exemplo de uso:
 * ```
 * class Usuario extends Model {
 *     // Defina propriedades e métodos específicos para o modelo Usuario
 * }
 * ```
 *
 * @package Model
 */
abstract class Model
{
    /**
     * Retorna o nome da tabela associada ao modelo.
     *
     * @return string
     */
    abstract public static function tableName(): string;

    /**
     * Retorna as colunas da tabela associada ao modelo.
     *
     * @return array
     */
    abstract public static function columns(): array;
}
