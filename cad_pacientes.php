<?php
require_once 'db_connection.php';


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'];
    $dataNasc = $_POST['dataNasc'];
    $genero = $_POST['genero'];
    $cpf = $_POST['cpf'];
    $sus = $_POST['sus'];
    $prontuario = $_POST['prontuario'];
    $cidadeNasc = $_POST['cidadeNasc'];
    $paisNasc = $_POST['paisNasc'];
    $nomeMae = $_POST['nomeMae'];
    $nomePai = $_POST['nomePai'];
    $unidadeSaude = $_POST['unidadeSaude'];
    $cep = $_POST['cep'];
    $endereco = $_POST['endereco'];
    $numero = $_POST['numero'];
    $bairro = $_POST['bairro'];
    $complemento = $_POST['complemento'];
    $cidade = $_POST['cidade'];
    $uf = $_POST['uf'];
    $referencia = $_POST['referencia'];
    $telefone = $_POST['telefone'];
    $celular = $_POST['celular'];
    $ufNasc = $_POST['ufNasc'];

    // Prepara a consulta SQL para inserir o paciente
    $sql = "INSERT INTO PICadPacientes (nome, dataNasc, genero, cpf, sus, prontuario, cidadeNasc, paisNasc, nomeMae, nomePai, unidadeSaude, cep, endereco, numero, bairro, complemento, cidade, uf, referencia, telefone, celular, ufNasc) 
    VALUES ('$nome', '$dataNasc', '$genero', '$cpf', '$sus', '$prontuario', '$cidadeNasc', '$paisNasc', '$nomeMae', '$nomePai', '$unidadeSaude', '$cep', '$endereco', '$numero', '$bairro', '$complemento', '$cidade', '$uf', '$referencia', '$telefone', '$celular', '$ufNasc')";

    // Executa a consulta
    if ($conn->query($sql) === TRUE) {
        echo '<script>alert("Paciente Cadastrado com sucesso!"); window.location.href = "lista_pacientes.php";</script>';
    } else {
        echo "Erro ao cadastrar paciente: " . $conn->error;
    }

    // Fecha a conexão com o banco de dados
    $conn->close();
}
