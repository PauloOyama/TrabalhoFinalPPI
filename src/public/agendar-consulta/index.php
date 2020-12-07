<?php
include_once "../db.php";

function return_err($msg, $code = 400)
{
    http_response_code($code);
    echo json_encode(["ERR_MSG" => $msg], JSON_UNESCAPED_UNICODE);
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $expected_keys = ["data_agendamento", "horario", "nome", "email", "telefone", "id_medico"];
    foreach ($expected_keys as $key)
        if (!array_key_exists($key, $_POST))
            return_err("Requisição inválida -- campos faltando");
    
    extract($_POST);
    
    $sql = <<<SQL
    INSERT INTO agenda (data_agendamento, horario, nome, email, telefone, codigo_medico)
    VALUES (?, ?, ?, ?, ?, ?)
    SQL;
    try {
        $query = $pdo->prepare($sql);
        $query->execute([$data_agendamento, $horario, $nome, $email, $telefone, $id_medico]);
    }
    catch (Exception $e) {
        return_err("Houve um erro no agendamento" . $e->getMessage(), 500);
    }
}
?>