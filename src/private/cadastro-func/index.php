<?php
if ($_SERVER["REQUEST_METHOD"] == "POST")
    header('Content-Type: application/json');

include_once "../../db.php";
include_once "../../common.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $expected_keys = [
        "nome", "email", "telefone", "cep", "logradouro",
        "bairro", "cidade", "estado", "data_in_contrato", "salario",
        "senha", "especialidade", "crm"
    ];
    validate_keys($expected_keys, $_POST);

    extract($_POST);
    try {
        $sql_pessoa = <<<SQL
        INSERT INTO pessoa VALUES
        (default, ?, ?, ?, ?, ?, ?, ?, ?);
        SQL;
        $sql_funcionario = <<<SQL
        INSERT INTO funcionario VALUES
        (?, ?, ?, ?);
        SQL;
        $sql_medico = <<<SQL
        INSERT INTO medico 
        VALUES (?, ?, ?);
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
        $query = $pdo->prepare($sql_funcionario);
        if (!$query->execute([$data_in_contrato, $salario, password_hash($senha, PASSWORD_DEFAULT), $id_pessoa]))
            throw new Exception("Falha na inserção em FUNCIONARIO");

        if (array_key_exists("medico", $_POST)) {
            $query = $pdo->prepare($sql_medico);
            if (!$query->execute([$especialidade, $crm, $id_pessoa]))
                throw new Exception("Falha na inserção em MEDICO");
        }
        $pdo->commit();
        echo json_encode(["STATUS" => true]);
    } catch (Exception $e) {
        $pdo->rollback();
        return_err("Houve um erro inesperado: " . $e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Cadastro De Funcionário</title>
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
            <h2 class="centralizaX">Cadastro de Funcionário</h2>
            <form class="row gx-2 gy-4" action="index.php" method="POST">
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
                    <input type="number" class="form-control" id="Cep" name="cep" required autocomplete="off" pattern="[0-9]{8}" />
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
                <div class="col-md-4">
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
                <div class="col-md-4">
                    <label for="Data" class="form-label">Data de Inicío</label>
                    <input type="date" class="form-control" id="Data" required name="data_in_contrato" autocomplete="off" />
                    <span></span>
                </div>
                <div class="col-md-4">
                    <label for="Salario" class="form-label">Salário</label>
                    <input type="text" class="form-control" id="Salario" required name="salario" autocomplete="off" />
                    <span></span>
                </div>
                <div class="col-md-12">
                    <label for="Senha" class="form-label">Senha</label>
                    <input type="password" class="form-control" id="Senha" required name="senha" autocomplete="off" />
                    <span></span>
                </div>
                <div class="col-md-12">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="Medico" name="medico" />
                        <label class="form-check-label" for="Medico">Você é médico ?</label>
                    </div>
                </div>
                <div class="isDoc">
                    <div class="col-md-5">
                        <label for="Especialidade" class="form-label">Especialidade</label>
                        <input type="text" class="form-control" id="Especialidade" name="especialidade" autocomplete="off" />
                        <span></span>
                    </div>
                    <div class="col-md-5">
                        <label for="CRM" class="form-label">CRM</label>
                        <input type="text" class="form-control" id="CRM" name="crm" autocomplete="off" />
                        <span></span>
                    </div>
                </div>
                <div class="col-md-12">
                    <button type="submit" class="btn" style="background-color: #f34213; color: #ffffff;">
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
        <script>
            function sendForm(event) {
                event.preventDefault();
                let formDados = new FormData(event.target);
                let modal = new bootstrap.Modal(document.getElementById("modal"));

                let httpReq = new XMLHttpRequest();
                httpReq.onreadystatechange = function() {
                    if (httpReq.readyState === XMLHttpRequest.DONE) {
                        if (httpReq.status === 200) document.getElementById("modal-text").innerText = "Funcionário cadastrado com sucesso";
                        else if (httpReq.status === 409) document.getElementById("modal-text").innerText = "Email já registrado";
                        else document.getElementById("modal-text").innerText = "Erro inesperado";
                        document.getElementById("modal-close").addEventListener("click", (event) => modal.hide());
                        modal.show();
                    }
                };
                httpReq.open("POST", "./");
                httpReq.send(formDados);
            }

            function preencheEndereco(event) {
                // Se o CEP ainda é inválido, termina a função
                if (!event.target.validity.valid) return;

                let httpReq = new XMLHttpRequest();
                httpReq.responseType = "json";
                httpReq.onreadystatechange = function() {
                    if (httpReq.readyState === XMLHttpRequest.DONE) {
                        if (httpReq.status === 200) {
                            let address = httpReq.response;
                            document.getElementById("Logradouro").value = address["logradouro"];
                            document.getElementById("Cidade").value = address["cidade"];
                            document.getElementById("Bairro").value = address["bairro"];
                            document.getElementById("ESTADO").value = address["estado"];
                        }
                    }
                };

                httpReq.open("GET", `./lista-endereco.php?cep=${encodeURIComponent(event.target.value)}`);
                httpReq.send();
            }

            window.onload = function() {
                const isDoc = document.querySelector(".isDoc");
                const openFormDoc = document.querySelector("input[name=medico]");
                openFormDoc.addEventListener("change", function() {
                    if (this.checked) {
                        isDoc.style.display = "block";
                        document.querySelector("input[name=crm]").setAttribute("required", "");
                        document.querySelector("input[name=especialidade]").setAttribute("required", "");
                    } else {
                        isDoc.style.display = "none";
                        document.querySelector("input[name=crm]").removeAttribute("required");
                        document.querySelector("input[name=especialidade]").removeAttribute("required");
                    }
                });

                document.querySelector("form").addEventListener("submit", sendForm);
                document.getElementById("Cep").addEventListener("input", preencheEndereco);
            };
        </script>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-popRpmFF9JQgExhfw5tZT4I9/CI5e2QcuUZPOVXb1m7qUmeR2b50u+YFEYe1wgzy" crossorigin="anonymous"></script>
    <footer>
        <p>® Copyright 2020. Todos os direitos reservados.</p>
    </footer>
</body>

</html>