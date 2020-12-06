<?php
include_once "../db.php";

function return_err($msg, $code = 400) {
    http_response_code($code);
    echo json_encode(["ERR_MSG" => $msg], JSON_UNESCAPED_UNICODE);
    exit();
}

function authenticate($pdo, $email, $senha) {
    $sql_pessoa = <<<SQL
    SELECT email, codigo
    FROM pessoa
    WHERE email = ?
    SQL;
    $sql_funcionario = <<<SQL
    SELECT senha_hash
    FROM funcionario
    WHERE codigo = ?
    SQL;

    try {
        $stmt = $pdo->prepare($sql_pessoa);
        $stmt->execute([$email]);
        $row = $stmt->fetch();
        if (!$row)
            return false; // nenhum resultado (email não encontrado)
        else {
            $stmt = $pdo->prepare($sql_funcionario);
            $stmt->execute([$row["codigo"]]);
            $hash_row = $stmt->fetch();
            if ($hash_row)  // garante a verificação só para query bem sucedida
                return password_verify($senha, $hash_row['senha_hash']);
            else
                return false;
        }
    }
    catch (Exception $e) {
        exit('Falha inesperada: ' . $e->getMessage());
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    header("Content-Type: application/json; charset=UTF-8");
    $expected_keys = ["email", "senha"];
    foreach ($expected_keys as $key)
        if (!array_key_exists($key, $_POST))
            return_err("Requisição inválida -- campos faltando");

    if (authenticate($pdo, $_POST["email"], $_POST["senha"])) {
        echo json_encode(["SUCESSO" => true]);
        exit();
    }
    else {
        http_response_code(401);
        echo json_encode(["SUCESSO" => false]);
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/utils.css">
    <link rel="stylesheet" href="../css's/login_page.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-CuOF+2SnTUfTwSZjCXf01h7uYhfOBuxIhGKPbfEJ3+FqH/s6cIFN9bGr1HmAg4fQ" crossorigin="anonymous">
    <title>Login Page</title>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top ">
        <div class="container">
            <img src="../src/cardiograma.svg" alt="Logo" class="logo" />
            <h2 class="navbar-brand">Clinica São Miguel</h2>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive"
                aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarResponsive">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item active">
                        <a class="nav-link" href="../">Quem Somos?
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../">Visão</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../">Valores</a>
                    </li>
                    <li class=" nav-item dropdown">
                        <a class="nav-link dropdown-toggle" id="navbarDropdown" role="button" data-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false">
                            Menu
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="../login/">Login</a>
                            <a class="dropdown-item " href="../novo-endereco/">Cadastro de Endereço</a>
                            <a class="dropdown-item " href="../galeria/">Galeria</a>


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
                    <input type="email" class="form-control" id="Email" placeholder="Ex: arel@vidal.com" name="email"
                        autocomplete="off">
                </div>

                <div class="col-md-12 ">
                    <label for="senha" class="form-label">Senha</label>
                    <input type="password" class="form-control" id="senha" name="senha" autocomplete="off">
                </div>

                <div class="col-md-12">
                    <button type="submit" class="btn btn-primary">
                        Cadastrar
                    </button>
                </div>

            </form>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-alpha3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-popRpmFF9JQgExhfw5tZT4I9/CI5e2QcuUZPOVXb1m7qUmeR2b50u+YFEYe1wgzy"
        crossorigin="anonymous"></script>
</body>
</html>