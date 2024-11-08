<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Status de Exames Realizados</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 2rem;
        }

        .box {
            width: 100%;
            max-width: 1200px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            background-color: #fff;
            margin-top: 2rem;
        }

        .box .title {
            padding: 1.5rem;
            font-size: 1.75rem;
            font-weight: 500;
            text-align: center;
            color: #333;
            border-bottom: 1px solid #ddd;
        }

        .box form {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 1.5rem;
        }

        .box form .btn-group {
            display: flex;
            gap: 0.5rem;
        }

        .table-container {
            padding: 1.5rem;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table thead th {
            background-color: #f0f2f5;
            font-weight: 600;
        }

        .table tbody tr:hover {
            background-color: #f8f9fa;
        }

        .modal-body .form-control {
            font-size: 0.95rem;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <div class="d-flex justify-content-between w-100">
            <div class="navbar-brand"><a href="inicio.html">Sistema</a></div>
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
        <h1 class="title">Consultar Exames Realizados</h1>
        <form class="d-flex" method="POST" action="">
            <input type="text" class="form-control" id="inp_busca" name="busca"
                   placeholder="Nome Completo ou Exame Realizado">
            <div class="btn-group">
                <button type="submit" class="btn btn-primary">Buscar</button>
                <button type="button" class="btn btn-secondary" onclick="window.location.reload()">Cancelar</button>
            </div>
        </form>
        <div class="table-container">
            <table class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th>Paciente</th>
                    <th>Exame</th>
                    <th>Data Pedido</th>
                    <th>Data Resultado</th>
                    <th>Data Entrega</th>
                    <th>Situação</th>
                    <th>Ações</th>
                </tr>
                </thead>
                <tbody>
                <?php
                require_once 'db_connection.php';

                $sql = "SELECT PiCadExamesRealizados.codigo, PICadPacientes.nome, PICadExames.exame, PiCadExamesRealizados.dataPedido, 
    PiCadExamesRealizados.dataResultado, PiCadExamesRealizados.dataEntrega, PiCadExamesRealizados.situacao
    FROM PiCadExamesRealizados
    INNER JOIN PICadPacientes ON PiCadExamesRealizados.codPaciente = PICadPacientes.codigo
    INNER JOIN PICadExames ON PiCadExamesRealizados.codExame = PICadExames.codigo";


                if (isset($_POST['busca'])) {
                    $pesq = '%' . $_POST['busca'] . '%';
                    $stmt = $conn->prepare($sql . " WHERE PICadPacientes.nome LIKE ? OR PICadExames.exame LIKE ?");
                    if ($stmt === false) {
                        die('Erro ao preparar a consulta: ' . $conn->error);
                    }
                    $stmt->bind_param("ss", $pesq, $pesq);
                } else {
                    $stmt = $conn->prepare($sql);
                    if ($stmt === false) {
                        die('Erro ao preparar a consulta: ' . $conn->error);
                    }
                }

                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['nome']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['exame']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['dataPedido']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['dataResultado']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['dataEntrega']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['situacao']) . "</td>";
                        echo "<td>
              <button type='button' class='btn btn-primary' data-bs-toggle='modal' data-bs-target='#editModal' 
              data-id='" . htmlspecialchars($row['codigo']) . "' data-situacao='" . htmlspecialchars($row['situacao']) . "' 
              data-data-entrega='" . htmlspecialchars($row['dataEntrega']) . "'>Editar</button> 
              <a href='excluir_examePaciente.php?codigo=" . htmlspecialchars($row['codigo']) . "' class='btn btn-danger'>Excluir</a>
              </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='7'>Nenhum exame encontrado</td></tr>";
                }

                $stmt->close();
                $conn->close();
                ?>


                </tbody>
            </table>
        </div>
    </div>
</div>


<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Editar Situação e Data de Entrega</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="atualizar_exame.php">
                    <input type="hidden" id="editId" name="codigo">
                    <div class="mb-3">
                        <label class="form-label">Situação</label>
                        <select class="form-control" name="situacao" required>
                            <option value="Pronto">Pronto</option>
                            <option value="Entregue">Entregue</option>
                            <option value="Aguardando">Aguardando</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Data de Entrega</label>
                        <input type="date" class="form-control" id="editDataEntrega" name="dataEntrega">
                    </div>
                    <button type="submit" class="btn btn-primary">Atualizar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        crossorigin="anonymous"></script>
<script>
    var editModal = document.getElementById('editModal');
    editModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        document.getElementById('editId').value = button.getAttribute('data-id');
        document.getElementById('editSituacao').value = button.getAttribute('data-situacao');
        document.getElementById('editDataEntrega').value = button.getAttribute('data-data-entrega');
    });
</script>
</body>
</html>
