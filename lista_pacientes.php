<?php

include 'db_connection.php';


function buscarPacientes($conn, $search = null)
{
    $query = "SELECT * FROM PICadPacientes";
    if ($search) {
        $query .= " WHERE nome LIKE ? OR cpf = ? OR sus = ? OR prontuario = ?";
        $stmt = $conn->prepare($query);
        $searchParam = "%$search%";
        $stmt->bind_param("ssss", $searchParam, $search, $search, $search);
    } else {
        $stmt = $conn->prepare($query);
    }
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

$pacientes = [];
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['search'])) {
    $search = $_GET['search'];
    $pacientes = buscarPacientes($conn, $search);
} else {
    $pacientes = buscarPacientes($conn);
}


if (isset($_GET['codigo'])) {
    $codigo = $_GET['codigo'];
    $stmt = $conn->prepare("SELECT endereco, numero, cidade, uf, cep FROM PICadPacientes WHERE codigo = ?");
    $stmt->bind_param("i", $codigo);
    $stmt->execute();
    $paciente = $stmt->get_result()->fetch_assoc();

    header('Content-Type: application/json');
    echo json_encode($paciente ? $paciente : ['erro' => 'Paciente não encontrado']);
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Lista de Pacientes</title>
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

        #mapContainer {
            height: 400px;
            width: 100%;
        }

        #modal, #overlay {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 1051;
        }

        #modal {
            background-color: white;
            padding: 20px;
            border: 1px solid #ccc;
            width: 80%;
            max-width: 600px;
        }

        #overlay {
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
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
                            <li><a class="dropdown-item" href="exames_pacientes.php">Vincular Exames a Pacientes</a></li>
                            <li><a class="dropdown-item" href="status_exames.php">Status de Exames Relizados</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
            <div>
                <button class="btn btn-outline-light" type="button">Sair</button>
            </div>
        </div>
    </div>
</nav>

<div class="container">
    <div class="box">
        <div class="title text-center mb-4">Lista de Pacientes</div>

        <form method="get" action="" class="mb-3 d-flex gap-3 justify-content-center">
            <input type="text" name="search" placeholder="Pesquisar por nome, CPF, SUS ou prontuário"
                   class="form-control w-50">
            <button type="submit" class="btn btn-primary">Pesquisar</button>
        </form>

        <div class="table-container">
            <table class="table table-striped table-bordered">
                <thead>
                <tr>
                    <th>Nome</th>
                    <th>CPF</th>
                    <th>SUS</th>
                    <th>Prontuário</th>
                    <th>Ação</th>
                    <th>Mapa</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($pacientes as $paciente): ?>
                    <tr>
                        <td><?= htmlspecialchars($paciente['nome']) ?></td>
                        <td><?= htmlspecialchars($paciente['cpf']) ?></td>
                        <td><?= htmlspecialchars($paciente['sus']) ?></td>
                        <td><?= htmlspecialchars($paciente['prontuario']) ?></td>
                        <td class="text-center">
                            <a href="alterar_paciente.php?codigo=<?= $paciente['codigo'] ?>"
                               class="btn btn-warning btn-sm">Alterar</a>
                            <a href="excluir_paciente.php?codigo=<?= $paciente['codigo'] ?>"
                               onclick="return confirm('Tem certeza que deseja excluir este paciente?');"
                               class="btn btn-danger btn-sm">Excluir</a>
                        </td>
                        <td class="text-center">
                            <button type="button" class="btn btn-info btn-sm"
                                    onclick="verNoMapa(<?= $paciente['codigo'] ?>)">Ver no Mapa
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>


<div id="overlay" onclick="fecharModal()"></div>
<div id="modal">
    <div id="mapContainer"></div>
    <p id="endereco" class="mt-3 text-center"></p>
    <button onclick="fecharModal()" class="btn btn-secondary w-100 mt-3">Fechar</button>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
<script src="https://js.api.here.com/v3/3.1/mapsjs-core.js"></script>
<script src="https://js.api.here.com/v3/3.1/mapsjs-service.js"></script>
<script>
    let platform;
    let map;
    let behavior;
    let ui;

    function verNoMapa(codigo) {
        fetch(`?codigo=${codigo}`)
            .then(response => {
                if (!response.ok) throw new Error("Erro na resposta do servidor");
                return response.json();
            })
            .then(data => {
                if (data.erro) {
                    alert("Erro: " + data.erro);
                    return;
                }

                const {endereco, numero, cidade, uf, cep} = data;
                const fullEndereco = `${endereco}, ${numero}, ${cidade} - ${uf}, ${cep}`;
                document.getElementById("endereco").textContent = fullEndereco;

                mostrarMapa(fullEndereco);

                // Exibe o modal e o overlay
                document.getElementById("modal").style.display = "block";
                document.getElementById("overlay").style.display = "block";
            })
            .catch(error => {
                console.error("Erro na requisição:", error);
            });
    }

    function fecharModal() {
        document.getElementById("modal").style.display = "none";
        document.getElementById("overlay").style.display = "none";
    }

    function mostrarMapa(endereco) {
        if (!platform) {
            platform = new H.service.Platform({
                apikey: '5ZiI__Wi2H8gQQToKOSBB_4U_9-I8umhDGPgeYJV34Q'
            });
        }

        const mapContainer = document.getElementById('mapContainer');

        if (!map) {
            const defaultLayers = platform.createDefaultLayers();
            map = new H.Map(mapContainer, defaultLayers.vector.normal.map, {
                zoom: 14,
                center: {lat: 0, lng: 0}
            });

            ui = H.ui.UI.createDefault(map, defaultLayers);
            behavior = new H.mapevents.Behavior(new H.mapevents.MapEvents(map));

            window.addEventListener('resize', () => map.getViewPort().resize());
        } else {
            map.setZoom(14);
        }

        map.removeObjects(map.getObjects());

        const geocoder = platform.getSearchService();
        geocoder.geocode({q: endereco}, (result) => {
            if (result.items.length) {
                const location = result.items[0].position;
                map.setCenter(location);
                map.addObject(new H.map.Marker(location));
                map.getViewPort().resize();
            } else {
                alert("Localização não encontrada.");
            }
        }, (error) => {
            alert("Erro na busca de localização: " + error);
        });
    }
</script>
</body>
</html>
