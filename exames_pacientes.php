<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Cadastro de Exame dos Pacientes</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Estilos principais */
        body, html {
            height: 100%;
            margin: 0;
            background-color: #f8f9fa;
        }

        /* Container centralizado */
        .container {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }

        /* Card principal */
        .box {
            background-color: #fff;
            padding: 30px;
            width: 100%;
            max-width: 800px;
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }

        /* Título estilizado */
        .box .title {
            font-size: 1.5rem;
            font-weight: bold;
            color: #343a40;
            margin-bottom: 20px;
            text-align: center;
            border-bottom: 2px solid #e9ecef;
            padding-bottom: 10px;
        }

        /* Estilo dos campos */
        .form-label {
            font-weight: 500;
            color: #495057;
        }

        /* Sugestões */
        .sugestoes {
            position: absolute;
            top: 100%;
            left: 0;
            background-color: #fff;
            border: 1px solid #ddd;
            z-index: 1000;
            width: 100%;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            display: none;
            max-height: 150px;
            overflow-y: auto;
        }

        .sugestoes li {
            padding: 10px;
            cursor: pointer;
            list-style-type: none;
        }

        .sugestoes li:hover {
            background-color: #f1f1f1;
        }

        /* Botão principal */
        .btn-primary {
            width: 100%;
        }

        /* Nav Bar */
        .navbar-dark .navbar-nav .nav-link {
            color: rgba(255, 255, 255, 0.8);
            font-weight: 500;
        }

        .navbar-dark .navbar-nav .nav-link:hover {
            color: #fff;
        }
    </style>
    <script src="js/sugestoes.js"></script>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="inicio.html">Sistema</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Pacientes
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="cadastro_pacientes.html">Cadastrar Pacientes</a></li>
                        <li><a class="dropdown-item" href="mapa.php">Listar Pacientes</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Exames
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="cad_exame.html">Cadastro de Exames</a></li>
                        <li><a class="dropdown-item" href="listar_exames.php">Lista de Exames</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Regulação
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="exames_pacientes.php">Vincular Exames a Pacientes</a></li>
                        <li><a class="dropdown-item" href="status_exames.php">Status de Exames Realizados</a></li>
                    </ul>
                </li>
            </ul>
            <button class="btn btn-outline-light" type="button">Sair</button>
        </div>
    </div>
</nav>

<div class="container">
    <div class="box">
        <h1 class="title">Cadastro de Exame dos Pacientes</h1>
        <form class="row g-3" method="post" action="cad_examesPacientes.php">
            <div class="col-md-6 form-group position-relative">
                <label for="idPaciente" class="form-label">Paciente:</label>
                <input class="form-control" type="text" id="idPaciente" name="idPaciente" autocomplete="off" required>
                <ul id="sugestoes" class="sugestoes"></ul>
            </div>
            <div class="col-md-6 form-group position-relative">
                <label for="idExame" class="form-label">Exame:</label>
                <input class="form-control" type="text" id="idExame" name="idExame" autocomplete="off">
                <ul id="sugestoesUsuario" class="sugestoes"></ul>
            </div>
            <div class="col-md-3">
                <label for="dataPedido" class="form-label">Data do Pedido:</label>
                <input type="date" class="form-control" name="dataPedido" autocomplete="off">
            </div>
            <div class="col-md-3">
                <label for="dataResultado" class="form-label">Data do Resultado:</label>
                <input type="date" class="form-control" name="dataResultado">
            </div>
            <div class="col-md-3">
                <label for="situacao" class="form-label">Situação</label>
                <select class="form-control" name="situacao" required>
                    <option value="Pronto">Pronto</option>
                    <option value="Entregue">Entregue</option>
                    <option value="Aguardando">Aguardando</option>
                </select>
            </div>
            <div class="col-md-3">
                <label for="dataEntrega" class="form-label">Data da Entrega:</label>
                <input type="date" class="form-control" name="dataEntrega">
            </div>
            <input type="hidden" id="idSelecionado" name="paciente" value="">
            <input type="hidden" id="idExameSelecionado" name="exame" value="">
            <div class="col-12">
                <button type="submit" class="btn btn-primary">Cadastrar</button>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
