<?php
include_once "../db.php";

function return_err($msg, $code = 400)
{
    http_response_code($code);
    echo json_encode(["ERR_MSG" => $msg], JSON_UNESCAPED_UNICODE);
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    header("Content-Type: application/json; charset=UTF-8");
    $expected_keys = ["cep", "logradouro", "bairro", "cidade", "estado"];
    foreach ($expected_keys as $key)
        if (!array_key_exists($key, $_POST))
            return_err("Requisição inválida -- campos faltando");

    // Verifica se o CEP já existe na base de dados
    $sql = <<<SQL
        SELECT CEP
        FROM base_enderecos_ajax
        WHERE CEP = ?
        SQL;
    $query = $pdo->prepare($sql);
    $query->execute([$_POST["cep"]]);
    if ($query->fetch()) {
        http_response_code(409);
        return_err("CEP já existente");
    }

    $sql = <<<SQL
        INSERT INTO base_enderecos_ajax (cep, logradouro, bairro, cidade, estado)
        VALUES (?, ?, ?, ?, ?)
        SQL;

    try {
        $query = $pdo->prepare($sql);
        $query->execute([$_POST["cep"], $_POST["logradouro"], $_POST["bairro"], $_POST["cidade"], $_POST["estado"]]);
        echo json_encode(["STATUS" => true]);
        exit();
    } catch (Exception $e) {
        return_err("Houve um erro na adição do endereço", 500);
    }
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
            <h2 class="centralizaX">Novo Endereço</h2>
            <form class="row gx-2 gy-4" action="./" method="POST" name="Cadastro">

                <div class="col-md-12 form-floating">

                    <input type="text" class="form-control" id="CEP" placeholder="CEP" name="cep" autocomplete="off" required>
                    <label for="CEP" class="form-label">CEP</label>
                    <span></span>
                </div>

                <div class="col-md-12 form-floating">
                    <input type="text" class="form-control" id="LOGRADOURO" placeholder="LOGRADOURO" name="logradouro" autocomplete="off">
                    <label for="LOGRADOURO" class="form-label">Logradouro</label>
                    <span></span>

                </div>

                <div class="col-md-12 form-floating">
                    <input type="text" class="form-control" id="BAIRRO" placeholder="BAIRRO" name="bairro" autocomplete="off">
                    <label for="BAIRRO" class="form-label">Bairro</label>
                    <span></span>

                </div>

                <div class="col-md-8">
                    <label for="CIDADE" class="form-label">Cidade</label>
                    <input type="text" class="form-control" id="CIDADE" name="cidade" autocomplete="off">
                    <span></span>
                </div>

                <div class="col-md-4">
                    <label for="ESTADO" class="form-label">Estado</label>
                    <select name="estado" id="ESTADO" class="form-select">
                        <option value="MG">MG</option>
                        <option value="SP">SP</option>
                        <option value="RJ">RJ</option>
                        <option value="BA">BA</option>
                        <span></span>
                    </select>
                </div>

                <div class="col-md-12">
                    <button type="submit" class="btn btn-primary">
                        Cadastrar
                    </button>
                </div>

            </form>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-popRpmFF9JQgExhfw5tZT4I9/CI5e2QcuUZPOVXb1m7qUmeR2b50u+YFEYe1wgzy" crossorigin="anonymous"></script>

</body>

</html>