<?php
if ($_SERVER["REQUEST_METHOD"] == "POST")
    header('Content-Type: application/json');
include_once "../../db.php";
include_once "../../common.php";

function authenticate($pdo, $email, $senha)
{
    $sql = <<<SQL
    SELECT senha_hash
    FROM pessoa INNER JOIN funcionario ON pessoa.codigo = funcionario.codigo
    WHERE email = ?
    SQL;

    $query = run_sql($pdo, $sql, [$email]);
    $row = $query->fetch();
    if (!$row)
        return_err("INVALID_EMAIL", 401);
    if (!password_verify($senha, $row["senha_hash"]))
        return_err("INVALID_PASS", 401);
    echo json_encode(["LOGIN" => true]);
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $expected_keys = ["email", "senha"];
    validate_keys($expected_keys, $_POST);

    extract($_POST);

    // Caso a autenticação não dê certo, um 401 unauthorized é retornado.
    authenticate($pdo, $email, $senha);
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/utils.css">
    <link rel="stylesheet" href="../css's/login_page.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-CuOF+2SnTUfTwSZjCXf01h7uYhfOBuxIhGKPbfEJ3+FqH/s6cIFN9bGr1HmAg4fQ" crossorigin="anonymous">
    <title>Login Page</title>
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
            <h2 class="centralizaX">Login</h2>
            <form class="row gx-2 gy-4" action="./" method="POST">

                <div class="col-md-12 ">
                    <label for="Email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="Email" placeholder="Ex: arel@vidal.com" name="email" autocomplete="off" required>
                </div>

                <div class="col-md-12 ">
                    <label for="senha" class="form-label">Senha</label>
                    <input type="password" class="form-control" id="senha" name="senha" autocomplete="off" required>
                </div>

                <div class="col-md-12 centralizaX">
                    <button type="submit" class="btn btn-lg btn-block" style="background-color: #f34213;color: #ffffff">
                        Entrar
                    </button>
                </div>

            </form>
            <div class="modal fade" id="modal-login">
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
        let modalLogin = new bootstrap.Modal(document.getElementById('modal-login'));

        function sendPageForm(event) {
            event.preventDefault();
            formInfo = new FormData(document.querySelector("form"));

            httpReq = new XMLHttpRequest();
            httpReq.responseType = "json";

            httpReq.onreadystatechange = function() {
                if (httpReq.readyState === XMLHttpRequest.DONE) {
                    if (httpReq.status === 200) {
                        document.getElementById("modal-text").innerText = "Login feito com sucesso.";
                        window.open(`${window.location.protocol}//${window.location.host}/private/`);
                        modalLogin.show();
                    } else if (httpReq.status === 401) {
                        if (httpReq.response["ERR"] == "INVALID_EMAIL")
                            document.getElementById("modal-text").innerText = "Email inválido";
                        else
                            document.getElementById("modal-text").innerText = "Senha inválida.";
                        modalLogin.show();
                    } else {
                        document.getElementById("modal-text").innerText = "Um erro inesperado ocorreu";
                        modalLogin.show();
                    }
                }
            }

            httpReq.open("POST", "./");
            httpReq.send(formInfo);
        }

        document.querySelector("form").addEventListener("submit", sendPageForm);
    </script>
</body>

</html>