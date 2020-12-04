<?php
include_once "../../db.php";

function return_err($msg, $code = 400) {
    http_response_code($code);
    echo json_encode(["ERR_MSG" => $msg], JSON_UNESCAPED_UNICODE);
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $expected_keys = ["cep", "logradouro", "bairro", "cidade", "estado"];
    foreach ($expected_keys as $key)
      if (!array_key_exists($key, $_POST))
          return_err("Requisição inválida -- campos faltando");

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
      <form class="row g-2">
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
          <button type="button" class="btn btn-primary">
            Enviar
          </button>
        </div>
      </form>
    </div>
    <script>
      console.log('aye')
      let form = document.querySelector("form");
      let btn = document.querySelector("button");
      btn.addEventListener("click", e => {
        req = new XMLHttpRequest();
        req.onreadystatechange = () => {
        if (req.status === 200)
          console.log('nicer!')
        }
        req.open('POST', 'index.php')
        req.send(new FormData(form))
      })
    </script>
  </body>
</html>
