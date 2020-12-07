<?php
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
        return false;
    return password_verify($senha, $row["senha_hash"]);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    header("Content-Type: application/json; charset=UTF-8");
    $expected_keys = ["email", "senha"];
    validate_keys($expected_keys, $_POST);

    extract($_POST);

    // Caso a autenticação não dê certo, um 401 unauthorized é retornado.
    if (authenticate($pdo, $email, $senha)) {
        echo json_encode(["LOGIN" => true]);
        exit();
    }
    return_err("INVALID_CREDS", 401);
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
                    <button type="submit" class="btn btn-primary btn-lg btn-block">
                        Entrar
                    </button>
                </div>

            </form>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-popRpmFF9JQgExhfw5tZT4I9/CI5e2QcuUZPOVXb1m7qUmeR2b50u+YFEYe1wgzy" crossorigin="anonymous"></script>
</body>

</html>