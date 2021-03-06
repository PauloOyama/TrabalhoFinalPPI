<?php
include_once "../../db.php";
include_once "../../common.php";

function authenticate($pdo, $email, $senha)
{
    $sql = <<<SQL
    SELECT senha_hash, pessoa.codigo
    FROM pessoa INNER JOIN funcionario ON pessoa.codigo = funcionario.codigo
    WHERE email = ?
    SQL;

    $query = run_sql($pdo, $sql, [$email]);
    $row = $query->fetch();
    if (!$row)
        return_err("INVALID_EMAIL", 401);
    if (!password_verify($senha, $row["senha_hash"]))
        return_err("INVALID_PASS", 401);

    // Executa a verificação se o funcionário médico, para configurar um cookie de sessão como médico
    // (métodos mais seguros incluem usar uma tabela com sessões porém isso é apenas uma demonstração)
    $code = $row["codigo"];
    $sql = "SELECT * FROM medico WHERE codigo = $code";
    $row = $pdo->query($sql);
    if ($row->fetch())
        setcookie("user", $code, 0, "/");
    else
        setcookie("user", "regular", 0, "/");

    echo json_encode(["LOGIN" => true]);
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    header('Content-Type: application/json');
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
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="/trabalhoFinal/utils.css" />
    <link rel="stylesheet" href="/trabalhoFinal/public/css's/login_page.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-CuOF+2SnTUfTwSZjCXf01h7uYhfOBuxIhGKPbfEJ3+FqH/s6cIFN9bGr1HmAg4fQ" crossorigin="anonymous" />
    <title>Login Page</title>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container">
            <img src="/trabalhoFinal/public/svg/cardiograma.svg" alt="Logo" class="logo" />
            <a class="navbar-brand" href="/trabalhoFinal/public/">Clínica São Miguel</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarResponsive">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item active">
                        <a class="nav-link" href="/trabalhoFinal/public/#quemSomos">Conheça-nos </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/trabalhoFinal/public/#visao">Visão</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/trabalhoFinal/public/#valores">Valores</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Menu
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="/trabalhoFinal/public/login/">Login</a>
                            <a class="dropdown-item" href="/trabalhoFinal/public/novo-endereco/">Cadastro de
                                Endereço</a>
                            <a class="dropdown-item" href="/trabalhoFinal/public/galeria/">Galeria</a>
                            <a class="dropdown-item" href="/trabalhoFinal/public/agendar-consulta/">Agendar Consulta</a>
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
                <div class="col-md-12">
                    <label for="Email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="Email" placeholder="Ex: arel@vidal.com" name="email" autocomplete="off" required />
                </div>

                <div class="col-md-12">
                    <label for="senha" class="form-label">Senha</label>
                    <input type="password" class="form-control" id="senha" name="senha" autocomplete="off" required />
                </div>
                <div class="col-md-12 d-grid">
                    <button type="submit" class="btn" style="background-color: #f34213; color: #ffffff;">Entrar</button>
                </div>
            </form>
            <div class="modal fade" id="modal-login">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-body" id="modal-text"></div>
                        <div class="modal-footer d-grid gap-2">
                            <button data-dismiss="modal" class="btn" style="background-color: #f34213; color: #ffffff;">Ok</button>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
    <div class="mt-5"></div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-popRpmFF9JQgExhfw5tZT4I9/CI5e2QcuUZPOVXb1m7qUmeR2b50u+YFEYe1wgzy" crossorigin="anonymous"></script>
    <script>
        let modalLogin = new bootstrap.Modal(document.getElementById("modal-login"));

        function sendPageForm(event) {
            event.preventDefault();
            formInfo = new FormData(document.querySelector("form"));

            httpReq = new XMLHttpRequest();
            httpReq.responseType = "json";

            httpReq.onreadystatechange = function() {
                if (httpReq.readyState === XMLHttpRequest.DONE) {
                    if (httpReq.status === 200) {
                        document.getElementById("modal-text").innerText = "Login feito com sucesso.";
                        window.open(`${window.location.protocol}//${window.location.host}/trabalhoFinal/private/`);
                        modalLogin.show();
                    } else if (httpReq.status === 401) {
                        if (httpReq.response["ERR"] == "INVALID_EMAIL") document.getElementById("modal-text").innerText = "Email inválido";
                        else document.getElementById("modal-text").innerText = "Senha inválida.";
                        modalLogin.show();
                    } else {
                        document.getElementById("modal-text").innerText = "Um erro inesperado ocorreu";
                        modalLogin.show();
                    }
                }
            };

            httpReq.open("POST", "./");
            httpReq.send(formInfo);
        }

        document.querySelector("form").addEventListener("submit", sendPageForm);
    </script>
    <footer>
        <p>® Copyright 2020. Todos os direitos reservados.</p>
    </footer>
</body>

</html>