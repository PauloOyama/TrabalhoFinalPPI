<?php

include_once "../../db.php";
include_once "../../common.php";

$sql = <<<SQL
SELECT *
FROM agenda
SQL;


$stmt = $pdo->query($sql);

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <title>Agendamento de consultas</title>

  <!-- Bootstrap core CSS -->
  <link rel="stylesheet" href="/utils.css">
  <link rel="stylesheet" href="/private/css's/index.css">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-CuOF+2SnTUfTwSZjCXf01h7uYhfOBuxIhGKPbfEJ3+FqH/s6cIFN9bGr1HmAg4fQ" crossorigin="anonymous">


</head>

<body>

  <!-- Navigation -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top ">
    <div class="container">
      <img src="/private/svg/cardiograma.svg" alt="Logo" class="logo" />
      <a class="navbar-brand" href="/private/">Clínica São Miguel</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive"
        aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarResponsive">
        <ul class="navbar-nav ml-auto">
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" id="navbarDropdown" role="button" data-toggle="dropdown"
              aria-haspopup="true" aria-expanded="false">
              Cadastro
            </a>
            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
              <a class="dropdown-item" href="/private/cadastro_func/index.html">Funcionários</a>
              <a class="dropdown-item " href="/private/">Pacientes</a>
            </div>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" id="navbarDropdown2" role="button" data-toggle="dropdown"
              aria-haspopup="true" aria-expanded="false">
              Lista de Dados
            </a>
            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown2">
              <a class="dropdown-item " href="/private/listagem_de_dados/lista_enderecos.html">Endereços</a>
              <a class="dropdown-item " href="/private/listagem_de_dados/lista_todos_agend.php">Agendamentos -
                Clientes</a>
              <a class="dropdown-item " href="/private/listagem_de_dados/lista_agend_med.html"
                style="color:blue;">Agendamentos - Funcionário</a>
              <a class="dropdown-item" href="/private/listagem_de_dados/lista_func.php">Funcionários</a>
              <a class="dropdown-item " href="/private/listagem_de_dados/lista_pacientes.php">Pacientes</a>
            </div>
          </li>
        </ul>
      </div>
    </div>
  </nav>
  <!-- Page Content -->
  <div class="container topics">

    <main id="homeMain">
    <h3 class="centralizaX">Consultas Agendadas</h3>
    <div class="table-responsive">
    <table class="table table-striped table-hover">
    <thead>
      <tr>
        <th>Horário</th>
        <th>Nome</th>
        <th>Email</th>
        <th>Telefone</th>
        <th>Data</th>
      </tr>
      </thead>

      <tbody>
      <?php

      $i = 0;

      while ($row = $stmt->fetch()) {

        global $i;
        $i++;

        $horario = htmlspecialchars($row['horario']);
        $nome = htmlspecialchars($row['nome']);
        $email = htmlspecialchars($row['email']);
        $telefone = htmlspecialchars($row['telefone']);

        $data = new DateTime($row['data_agendamento']);
        $dataFormatoDiaMesAno = $data->format('d-m-Y');

        echo <<<HTML
          <tr>
            <td>$horario</td> 
            <td>$nome</td> 
            <td>$email</td>
            <td>$telefone</td>
            <td>$dataFormatoDiaMesAno</td>
         
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
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-alpha3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-popRpmFF9JQgExhfw5tZT4I9/CI5e2QcuUZPOVXb1m7qUmeR2b50u+YFEYe1wgzy"
    crossorigin="anonymous"></script>
</body>
</html>