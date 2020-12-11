<?php
if ($_SERVER["REQUEST_METHOD"] == "POST")
  header('Content-Type: application/json');

include_once "../db.php";
include_once "../common.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $expected_keys = [
    "nome", "email", "telefone", "cep", "logradouro",
    "bairro", "cidade", "estado", "peso", "altura", "tipo"
  ];
  validate_keys($expected_keys, $_POST);

  extract($_POST);
  try {
    $sql_pessoa = <<<SQL
        INSERT INTO pessoa VALUES
        (default, ?, ?, ?, ?, ?, ?, ?, ?);
        SQL;

    $sql_paciente = <<<SQL
        INSERT INTO paciente VALUES
        (?, ?, ?,?);
        SQL;
    $sql_verifica_email = <<<SQL
        SELECT email FROM pessoa WHERE email = ?
        SQL;

    $query = run_sql($pdo, $sql_verifica_email, [$email]);
    if ($query->fetch())
      return_err("EMAIL_EXISTS", 409);

    $pdo->beginTransaction();
    $query = $pdo->prepare($sql_pessoa);
    if (!$query->execute([$nome, $email, $telefone, $cep, $logradouro, $bairro, $cidade, $estado]))
      throw new Exception("Falha inserção em PESSOA");

    $id_pessoa = $pdo->lastInsertId();

    $query = $pdo->prepare($sql_paciente);
    if (!$query->execute([$peso, $altura, $tipo, $id_pessoa]))
      throw new Exception("Falha na inserção em PACIENTE");

    $pdo->commit();
    echo json_encode(["STATUS" => true]);
  } 
  catch (Exception $e) {
    $pdo->rollback();
    return_err("Houve um erro inesperado: " . $e->getMessage());
  }
  exit();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Cadastro De Paciente</title>

        <!-- Bootstrap core CSS -->
        <link rel="stylesheet" href="/utils.css" />
        <link rel="stylesheet" href="/private/css's/index.css" />

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-CuOF+2SnTUfTwSZjCXf01h7uYhfOBuxIhGKPbfEJ3+FqH/s6cIFN9bGr1HmAg4fQ" crossorigin="anonymous" />
    </head>

    <body>
        <!-- Navigation -->
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
            <div class="container">
                <img src="/private/svg/cardiograma.svg" alt="Logo" class="logo" />
                <a class="navbar-brand" href="/private/">Clínica São Miguel</a>
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
                                <a class="dropdown-item" href="/private/cadastro-func/">Funcionários</a>
                                <a class="dropdown-item" href="/private/">Pacientes</a>
                            </div>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" id="navbarDropdown2" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Lista de Dados
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown2">
                                <a class="dropdown-item" href="/private/listagem_de_dados/lista_todos_agend.php">Agendamentos - Clientes</a>
                                <a class="dropdown-item" href="/private/listagem_de_dados/lista_agend_med.php">Agendamentos - Funcionário</a>
                                <a class="dropdown-item" href="/private/listagem_de_dados/lista_func.php">Funcionários</a>
                                <a class="dropdown-item" href="/private/listagem_de_dados/lista_pacientes.php">Pacientes</a>
                                <a class="dropdown-item" href="/private/listagem_de_dados/lista_enderecos.php">Endereços</a>
                                <a class="dropdown-item " href="/public/index.html">Sair</a>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <!-- Page Content -->
        <div class="container topics">
            <main id="homeMain">
                <h2 class="centralizaX">Cadastro de Paciente</h2>
                <form class="row gx-2 gy-4" method="POST" name="Cadastro">
                    <div class="col-md-12">
                        <label for="nome" class="form-label">Nome</label>
                        <input type="text" class="form-control" id="nome" name="nome" autocomplete="off" required />
                        <span></span>
                    </div>
                    <div class="col-md-8">
                        <label for="Email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="Email" placeholder="Ex: ariel@vidal.com" required name="email" autocomplete="off" />
                        <span></span>
                    </div>
                    <div class="col-md-4">
                        <label for="Telefone" class="form-label">Telefone</label>
                        <input type="tel" class="form-control" id="Telefone" name="telefone" placeholder="xxxxxxxxxxx" required autocomplete="off" pattern="[0-9]{11}" />
                        <span></span>
                    </div>
                    <div class="col-md-4">
                        <label for="Cep" class="form-label">CEP</label>
                        <input type="text" class="form-control" id="Cep" name="cep" required autocomplete="off" pattern="[0-9]{8}" />
                        <span></span>
                    </div>

                    <div class="col-md-8">
                        <label for="Logradouro" class="form-label">Logradouro</label>
                        <input type="text" class="form-control" id="Logradouro" required name="logradouro" autocomplete="off" />
                        <span></span>
                    </div>
                    <div class="col-md-12">
                        <label for="Cidade" class="form-label">Cidade</label>
                        <input type="text" class="form-control" id="Cidade" required name="cidade" autocomplete="off" />
                        <span></span>
                    </div>
                    <div class="col-md-12">
                        <label for="Bairro" class="form-label">Bairro</label>
                        <input type="text" class="form-control" id="Bairro" required name="bairro" autocomplete="off" />
                        <span></span>
                    </div>
                    <div class="col-md-3">
                        <label for="ESTADO" class="form-label">Estado</label>
                        <select name="estado" id="ESTADO" class="form-select" required>
                            <option disabled selected value> - - - </option>
                            <option value="AC">AC</option>
                            <option value="AL">AL</option>
                            <option value="AP">AP</option>
                            <option value="AM">AM</option>
                            <option value="BA">BA</option>
                            <option value="CE">CE</option>
                            <option value="DF">DF</option>
                            <option value="ES">ES</option>
                            <option value="GO">GO</option>
                            <option value="MA">MA</option>
                            <option value="MT">MT</option>
                            <option value="MS">MS</option>
                            <option value="MG">MG</option>
                            <option value="PA">PA</option>
                            <option value="PB">PB</option>
                            <option value="PR">PR</option>
                            <option value="PE">PE</option>
                            <option value="PI">PI</option>
                            <option value="RJ">RJ</option>
                            <option value="RN">RN</option>
                            <option value="RS">RS</option>
                            <option value="RO">RO</option>
                            <option value="RR">RR</option>
                            <option value="SC">SC</option>
                            <option value="SP">SP</option>
                            <option value="SE">SE</option>
                            <option value="TO">TO</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label for="Peso" class="form-label">Peso</label>
                        <input type="text" class="form-control" id="Peso" required name="peso" autocomplete="off" />
                        <span></span>
                    </div>
                    <div class="col-md-3">
                        <label for="Altura" class="form-label">Altura</label>
                        <input type="text" class="form-control" id="Altura" required name="altura" autocomplete="off" />
                        <span></span>
                    </div>
                    <div class="col-md-3">
                        <label for="Tipo" class="form-label">Tipo</label>
                        <select name="tipo" id="Tipo" class="form-select" required>
                            <option disabled selected value> - - - </option>
                            <option value="A+">A+</option>
                            <option value="A-">A-</option>
                            <option value="B+">B+</option>
                            <option value="B-">B-</option>
                            <option value="AB+">AB+</option>
                            <option value="AB-">AB-</option>
                            <option value="O+">O+</option>
                            <option value="O-">O-</option>
                        </select>
                    </div>
                    <div class="col-md-12">
                        <button id="btn-submit" class="btn" style="background-color: #f34213; color: #ffffff;">
                            Cadastrar
                        </button>
                    </div>
                </form>

                <div class="modal fade" id="modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div id="modal-text" class="modal-body"></div>
                            <div class="modal-footer">
                                <button type="button" class="btn" data-bs-dismiss="modal" id="modal-close" style="background-color: #f34213; color: #ffffff;">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
            <div class="mt-5"></div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-popRpmFF9JQgExhfw5tZT4I9/CI5e2QcuUZPOVXb1m7qUmeR2b50u+YFEYe1wgzy" crossorigin="anonymous"></script>
        <script>
            function sendForm(event) {
                event.preventDefault();
                let dadosForm = new FormData(event.target);
                let modal = new bootstrap.Modal(document.getElementById("modal"));

                let httpReq = new XMLHttpRequest();
                httpReq.onreadystatechange = function () {
                    if (httpReq.readyState === XMLHttpRequest.DONE) {
                        if (httpReq.status === 200) document.getElementById("modal-text").innerText = "Paciente cadastrado com sucesso";
                        else if (httpReq.status === 409) document.getElementById("modal-text").innerText = "Email já registrado";
                        else document.getElementById("modal-text").innerText = "Erro inesperado";
                        document.getElementById("modal-close").addEventListener("click", (event) => modal.hide());
                        modal.show();
                    }
                };

                httpReq.open("POST", "./");
                httpReq.send(dadosForm);
            }

            window.onload = function () {
                document.querySelector("form").addEventListener("submit", sendForm);
            };
        </script>
    </body>
</html>
