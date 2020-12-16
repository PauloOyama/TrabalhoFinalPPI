<?php
include_once "../../db.php";
include_once "../../common.php";

// Os cookies de médicos são numéricos e possuem seu código de pessoa
if (!array_key_exists("user", $_COOKIE) || !is_numeric($_COOKIE["user"])) {
    header("Location: lista_agend_is_not_med.html");
    exit();
}

$cod = $_COOKIE["user"];

$sql = <<<SQL
    SELECT data_agendamento, horario, agenda.nome, agenda.email, agenda.telefone, pessoa.nome as nome_medico
    FROM agenda INNER JOIN pessoa ON agenda.codigo_medico = pessoa.codigo
    WHERE pessoa.codigo = ?
SQL;

// Uso de prepared statements pois o usuário pode alterar cookies localmente
$stmt = $pdo->prepare($sql);
$stmt->execute([$cod]);
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Clínica São Miguel - Private</title>

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="/trabalhoFinal/utils.css" />
    <link rel="stylesheet" href="/trabalhoFinal/private/css's/index.css" />

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-CuOF+2SnTUfTwSZjCXf01h7uYhfOBuxIhGKPbfEJ3+FqH/s6cIFN9bGr1HmAg4fQ" crossorigin="anonymous" />
</head>

<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container">
            <img src="/trabalhoFinal/private/svg/cardiograma.svg" alt="Logo" class="logo" />
            <a class="navbar-brand" href="/trabalhoFinal/private/">Clínica São Miguel</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarResponsive">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Cadastro
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="/trabalhoFinal/private/cadastro-func/">Funcionários</a>
                            <a class="dropdown-item" href="/trabalhoFinal/private/">Pacientes</a>
                        </div>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" id="navbarDropdown2" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Lista de Dados
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown2">
                            <a class="dropdown-item" href="/trabalhoFinal/private/listagem_de_dados/lista_todos_agend.php">Agendamentos - Clientes</a>
                            <a class="dropdown-item" href="/trabalhoFinal/private/listagem_de_dados/lista_agend_med.php">Agendamentos - Funcionário</a>
                            <a class="dropdown-item" href="/trabalhoFinal/private/listagem_de_dados/lista_func.php">Funcionários</a>
                            <a class="dropdown-item" href="/trabalhoFinal/private/listagem_de_dados/lista_pacientes.php">Pacientes</a>
                            <a class="dropdown-item" href="/trabalhoFinal/private/listagem_de_dados/lista_enderecos.php">Endereços</a>
                            <a class="dropdown-item " href="/trabalhoFinal/public/index.html">Sair</a>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Page Content -->
    <div class="container topics">
        <main id="homeMain">
            <h3 class="centralizaX" id="simpleMargin">Suas Consultas</h3>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Data</th>
                            <th>Horário</th>
                            <th>Paciente</th>
                            <th>Email</th>
                            <th>Telefone</th>
                            <th>Médico</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php
                        $i = 0;

                        while ($row = $stmt->fetch()) {

                            $i++;

                            $horario = htmlspecialchars($row['horario']);
                            $nome = htmlspecialchars($row['nome']);
                            $nome_medico = htmlspecialchars($row['nome_medico']);
                            $email = htmlspecialchars($row['email']);
                            $telefone = htmlspecialchars($row['telefone']);

                            $data = new DateTime($row['data_agendamento']);
                            $dataFormatoDiaMesAno = $data->format('d-m-Y');

                            echo <<<HTML
                                    <tr>
                                        <td>$i</td>
                                        <td>$dataFormatoDiaMesAno</td>
                                        <td>$horario</td>
                                        <td>$nome</td>
                                        <td>$email</td>
                                        <td>$telefone</td>
                                        <td>$nome_medico</td>

                                    </tr>
                                    HTML;
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </main>
        <div class="mt-5"></div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-popRpmFF9JQgExhfw5tZT4I9/CI5e2QcuUZPOVXb1m7qUmeR2b50u+YFEYe1wgzy" crossorigin="anonymous"></script>
    <footer>
        <p>® Copyright 2020. Todos os direitos reservados.</p>
    </footer>
</body>

</html>