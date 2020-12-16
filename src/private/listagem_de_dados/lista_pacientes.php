<?php

include_once "../../db.php";
include_once "../../common.php";

$sql = <<<SQL
    SELECT nome, email, telefone, cep, logradouro, bairro, cidade, estado,
    peso, altura, tipo_sanguineo
    FROM pessoa INNER JOIN paciente ON pessoa.codigo = paciente.codigo
SQL;


$stmt = $pdo->query($sql);

?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Tabela Pacientes</title>

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
            <h3 class="centralizaX" id="simpleMargin">Lista de Pacientes</h3>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Nome</th>
                            <th scope="col">Email</th>
                            <th scope="col">Telefone</th>
                            <th scope="col">CEP</th>
                            <th scope="col">Logradouro</th>
                            <th scope="col">Bairro</th>
                            <th scope="col">Cidade</th>
                            <th scope="col">Estado</th>
                            <th scope="col">Peso</th>
                            <th scope="col">Altura</th>
                            <th scope="col">Tipo Sanguíneo</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php
                        $i = 0;
                        while ($row = $stmt->fetch()) {
                            $i++;
                            $nome = htmlspecialchars($row['nome']);
                            $email = htmlspecialchars($row['email']);
                            $telefone = htmlspecialchars($row['telefone']);
                            $cep = htmlspecialchars($row['cep']);
                            $logradouro = htmlspecialchars($row['logradouro']);
                            $bairro = htmlspecialchars($row['bairro']);
                            $cidade = htmlspecialchars($row['cidade']);
                            $estado = htmlspecialchars($row['estado']);
                            $peso = htmlspecialchars($row['peso']);
                            $altura = htmlspecialchars($row['altura']);
                            $tipo_sanguineo = htmlspecialchars($row['tipo_sanguineo']);

                            echo <<<HTML
                                    <tr>
                                        <th scope="row">$i</th>
                                        <td >$nome</td>
                                        <td >$email</td>
                                        <td >$telefone</td>
                                        <td >$cep</td>
                                        <td >$logradouro</td>
                                        <td >$bairro</td>
                                        <td >$cidade</td>
                                        <td >$estado</td>
                                        <td >$peso</td>
                                        <td >$altura</td>
                                        <td >$tipo_sanguineo</td>
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