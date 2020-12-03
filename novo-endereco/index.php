<?php
function return_err($msg, $code = 400) {
    http_response_code($code);
    echo json_encode(["ERR_MSG" => $msg], JSON_UNESCAPED_UNICODE);
    exit();
}

function post_return() {
    $db_host = "db";
    $db_name = "clinica";
    $db_username = "root";
    $db_password = "docker is cool";

    $dsn = "mysql:host=$db_host; dbname=$db_name; charset=utf8mb4";
    $options = [
        PDO::ATTR_EMULATE_PREPARES => false,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ];

    try {
        $pdo = new PDO($dsn, $db_username, $db_password, $options);
    }
    catch (Exception $e) {
        $msg = $e->getMessage();
        return_err("Não foi possível conectar ao banco de dados: $msg", 500);
    }

    // Início do processo de validação/armazenamento de dados
    $expected_keys = ["cep", "logradouro", "bairro", "cidade", "estado"];
    foreach ($expected_keys as $key) {
        if (!array_key_exists($key, $_POST)) {
            return_err("Requisição inválida -- campos faltando.");
        }
    }

    $sql = <<<SQL
        INSERT INTO base_enderecos_ajax (cep, logradouro, bairro, cidade, estado)
        VALUES (?, ?, ?, ?, ?)
        SQL;
    
    try {
        $query = $pdo->prepare($sql);
        $query->execute([$_POST["cep"], $_POST["logradouro"], $_POST["bairro"], $_POST["cidade"], $_POST["estado"]]);
    }
    catch (Exception $e) {
        return_err("Houve um erro na adição do endereço", 500);
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    post_return();
    exit();
}
?>
<!doctype html>
<html lang="pt-BR">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-CuOF+2SnTUfTwSZjCXf01h7uYhfOBuxIhGKPbfEJ3+FqH/s6cIFN9bGr1HmAg4fQ" crossorigin="anonymous">

    <title>Clínica XYZ: Cadastro de Endereço</title>
  </head>
  <body>
    <div class="container">
      <form class="row g-2" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <div class="col-sm-3">
          <div class="form-floating">
            <input type="text" class="form-control" id="cep" placeholder="00000000000" name="cep">
            <label for="cep">CEP</label>
          </div>
        </div>
        <div class="col-sm-9">
          <div class="form-floating">
            <input type="text" class="form-control" id="bairro" placeholder="Bairro" name="bairro" required>
            <label for="bairro">Bairro</label>
          </div>
        </div>
        <div class="col-sm-12">
          <div class="form-floating">
            <input type="text" class="form-control" id="logradouro" placeholder="Rua" name="logradouro" required>
            <label for="logradouro">Logradouro</label>
          </div>
        </div>
        <div class="col-sm-9">
          <div class="form-floating">
            <input type="text" class="form-control" id="cidade" placeholder="cidade" name="cidade" required>
            <label for="cidade">Cidade</label>
          </div>
        </div>
        <div class="col-sm-3">
          <div class="form-floating">
            <select class="form-select" id="estado" aria-labe="estado" name="estado" required>
              <option selected>MG</option>
              <option>SP</option>
            </select>
            <label for="estado">Estado</label>
          </div>
        </div>

        <div class="col-12">
          <button type="submit" class="btn btn-primary">
            Enviar
          </button>
        </div>
      </form>
    </div>
  </body>
</html>
