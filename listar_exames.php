<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cadastro de Pedidos Retirados</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        .container {
            max-width: 100%;
            padding: 3rem 1rem;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .box {
            width: 100%;
            max-width: 900px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            background-color: white;
        }

        .box .title {
            padding-bottom: 20px;
            font-size: 24px;
            border-bottom: 1px solid #ccc;
        }

        .box form {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            padding: 1rem 0;
        }

        .btn-group {
            display: flex;
            gap: 1rem;
        }

        body {
            background-color: #f8f9fa;
            margin: 0;
        }

        .table-container {
            margin-top: 20px;
        }

        .table thead th {
            background-color: #f8f9fa;
            color: #495057;
        }

        .table tbody tr:nth-child(odd) {
            background-color: #f9f9f9;
        }

        .table tbody tr:hover {
            background-color: #e9ecef;
        }

        .table td a {
            margin-right: 5px;
        }
    </style>
</head>
<body>


<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <div class="d-flex justify-content-between w-100">
            <a class="navbar-brand" href="inicio.html">Sistema de Gestão Hospitalar</a>
            <div class="collapse navbar-collapse justify-content-center" id="navbarCenteredExample">
                <ul class="navbar-nav mb-2 mb-lg-0">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                           aria-expanded="false">
                            Pacientes
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="cad_pacientes.html">Cadastrar Pacientes</a></li>
                            <li><a class="dropdown-item" href="lista_pacientes.php">Listar Pacientes</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                           aria-expanded="false">
                            Exames
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="cad_exame.html">Cadastro de Exames</a></li>
                            <li><a class="dropdown-item" href="listar_exames.php">Lista de Exames</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                           aria-expanded="false">
                            Regulação
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="exames_pacientes.php">Vincular Exames a Pacientes</a>
                            </li>
                            <li><a class="dropdown-item" href="status_exames.php">Status de Exames Relizados</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>


<div class="container">
    <div class="box">
        <h1 class="title">Consultar Exames</h1>
        <form class="row g-3" method="POST" action="">
            <div class="col-md-8">
                <input type="text" class="form-control" id="inp_busca" name="busca" placeholder="Nome do Exame">
            </div>
            <div class="col-md-4 btn-group">
                <button type="submit" class="btn btn-primary">Buscar</button>
                <button type="button" class="btn btn-secondary" onclick="window.location.href=window.location.href">
                    Cancelar
                </button>
            </div>
        </form>


        <div class="table-container">
            <table class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Exame</th>
                    <th scope="col">Ações</th>
                </tr>
                </thead>
                <tbody>
                <?php

                require_once 'db_connection.php';


                $sql = "SELECT * FROM PICadExames";


                if (!empty($_POST['busca'])) {
                    $busca = $_POST['busca'];
                    $sql .= " WHERE exame LIKE '%$busca%'";
                }

                $result = $conn->query($sql);

                if ($result->num_rows > 0) {

                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['codigo'] . "</td>";
                        echo "<td>" . $row['exame'] . "</td>";
                        echo "<td><a href='alterar_exame.php?codigo=" . $row['codigo'] . "' class='btn btn-primary btn-sm'>Editar</a> <a href='excluir_exame.php?codigo=" . $row['codigo'] . "' class='btn btn-danger btn-sm'>Excluir</a></td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='3'>Nenhum exame encontrado.</td></tr>";
                }


                $conn->close();
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>
</html>
