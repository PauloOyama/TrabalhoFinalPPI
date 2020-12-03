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
        $query->execute([$payload["cep"], $payload["logradouro"], $payload["bairro"], $payload["cidade"], $payload["estado"]]);
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
<!-- HTML para a página visual será inserido aqui -->