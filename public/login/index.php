<?php
include_once "../../db.php";

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