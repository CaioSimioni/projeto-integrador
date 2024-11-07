<?php

require_once 'db_connection.php'; // Inclui a conexão com o banco de dados

session_start(); // Inicia a sessão

$error = ''; // Inicializa a variável de erro

if (isset($_POST['usuario']) && isset($_POST['senha'])) {
    $usuario = $_POST['usuario'];
    $senha = $_POST['senha'];

    // Verifica se a conexão com o banco de dados falhou
    if ($conn->connect_error) {
        die("Conexão falhou: " . $conn->connect_error);
    }

    // Consulta SQL simples
    $sql = "SELECT codigo FROM PICadUsuarios WHERE usuario = '$usuario' AND senha = '$senha'";
    $result = $conn->query($sql);

    // Verifica se o usuário existe
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $id = $row['id'];

        // Define variáveis de sessão
        $_SESSION['usuario_id'] = $id;
        $_SESSION['usuario'] = $usuario;

        // Redireciona para a página inicial
        header("Location: inicio.html");
        exit();
    } else {
        $error = "Usuário ou senha incorretos.";
    }

    // Fecha a conexão
    $conn->close();
} else {
    $error = "Por favor, preencha todos os campos.";
}

// Exibe a mensagem de erro em um message box e redireciona para index.php
if ($error) {
    echo "<script>
            alert('$error');
            window.location.href = 'index.html';
          </script>";
}
?>
