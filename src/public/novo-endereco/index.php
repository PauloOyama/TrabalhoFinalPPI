<?php
if ($_SERVER["REQUEST_METHOD"] == "POST")
    header('Content-Type: application/json');
include_once "../../db.php";
include_once "../../common.php";


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $expected_keys = ["cep", "logradouro", "bairro", "cidade", "estado"];
    validate_keys($expected_keys, $_POST);

    extract($_POST);

    // Verifica se o CEP já existe na base de dados
    $sql = <<<SQL
        SELECT CEP
        FROM base_enderecos_ajax
        WHERE CEP = ?
        SQL;
    $query = run_sql($pdo, $sql, [$cep]);
    if ($query->fetch())
        return_err("CEP_EXISTS", 409);

    // Insere o endereço
    $sql = <<<SQL
        INSERT INTO base_enderecos_ajax (cep, logradouro, bairro, cidade, estado)
        VALUES (?, ?, ?, ?, ?)
        SQL;
    $query = run_sql($pdo, $sql, [$cep, $logradouro, $bairro, $cidade, $estado]);
    echo json_encode(["STATUS" => "ADDR_ADDED"]);
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/utils.css">
    <link rel="stylesheet" href="../css's/novo_endereco.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-CuOF+2SnTUfTwSZjCXf01h7uYhfOBuxIhGKPbfEJ3+FqH/s6cIFN9bGr1HmAg4fQ" crossorigin="anonymous">
    <title>Cadastro novo endereço</title>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top ">
        <div class="container">
            <img src="/public/svg/cardiograma.svg" alt="Logo" class="logo" />
            <h2 class="navbar-brand">Clínica São Miguel</h2>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarResponsive">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item active">
                        <a class="nav-link" href="/public/#quemSomos">Conheça-nos
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/public/#visao">Visão</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/public/#valores">Valores</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Menu
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="/public/login/">Login</a>
                            <a class="dropdown-item " href="/public/novo-endereco/">Cadastro de Endereço</a>
                            <a class="dropdown-item " href="/public/galeria/">Galeria</a>
                            <a class="dropdown-item " href="/public/agendar-consulta/">Agendar Consulta</a>


                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </nav>


    <div class="container topics">
        <main id="homeMain">
            <h2 class="centralizaX" id="simpleMargin">Novo Endereço</h2>
            <form class="row gx-2 gy-4" action="./" method="POST" name="Cadastro">

                <div class="col-md-4 form-floating">

                    <input type="text" class="form-control" id="CEP" placeholder="CEP" name="cep" autocomplete="off" required>
                    <label for="CEP" class="form-label">CEP</label>
                    <span></span>
                </div>

                <div class="col-md-8 form-floating">
                    <input type="text" class="form-control" id="LOGRADOURO" placeholder="LOGRADOURO" name="logradouro" autocomplete="off" required>
                    <label for="LOGRADOURO" class="form-label">Logradouro</label>
                    <span></span>

                </div>

                <div class="col-md-12 form-floating">
                    <input type="text" class="form-control" id="BAIRRO" placeholder="BAIRRO" name="bairro" autocomplete="off" required>
                    <label for="BAIRRO" class="form-label">Bairro</label>
                    <span></span>

                </div>

                <div class="col-md-8">
                    <label for="CIDADE" class="form-label">Cidade</label>
                    <input type="text" class="form-control" id="CIDADE" name="cidade" autocomplete="off" required>
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
                        <span></span>
                    </select>
                </div>

                <div class="col-md-12">
                    <button type="submit" class="btn " style="background-color: #f34213;color: #ffffff">
                        Cadastrar
                    </button>
                </div>

            </form>

            <div class="modal fade" id="modal-add">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-body" id="modal-text">
                        </div>
                        <div class="modal-footer">
                            <button data-dismiss="modal" class="btn " style="background-color: #f34213;color: #ffffff">Ok</button>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-popRpmFF9JQgExhfw5tZT4I9/CI5e2QcuUZPOVXb1m7qUmeR2b50u+YFEYe1wgzy" crossorigin="anonymous"></script>
    <script>
        let modalSucesso = new bootstrap.Modal(document.getElementById('modal-add'));

        function sendPageForm(event) {
            event.preventDefault();
            formInfo = new FormData(document.querySelector("form"));

            httpReq = new XMLHttpRequest();

            httpReq.onreadystatechange = function() {
                if (httpReq.readyState === XMLHttpRequest.DONE) {
                    if (httpReq.status === 200) {
                        document.getElementById("modal-text").innerText = "Endereço adicionado com sucesso";
                        modalSucesso.show();
                    } else if (httpReq.status === 409) {
                        document.getElementById("modal-text").innerText = "Este CEP já existe";
                        modalSucesso.show();
                    } else
                        alert("Falha crítica");
                }
            }

            httpReq.open("POST", "./");
            httpReq.send(formInfo);
        }

        document.querySelector("form").addEventListener("submit", sendPageForm);
    </script>
</body>

</html>