<?php
// Arquivo de funções padrão no projeto


// Retorna um erro especificado
function return_err($msg, $code = 400) {
    http_response_code($code);
    echo json_encode(["ERR" => $msg], JSON_UNESCAPED_UNICODE);
    exit();
}

// Executa uma query SQL já em seu bloco try catch
function run_sql($pdo, $sql_query, $sql_values) {
    try {
        $prepared_query = $pdo->prepare($sql_query);
        $prepared_query->execute($sql_values);
        return $prepared_query;
    }
    catch (Exception $e) {
        return_err("SQL_UNEXPECTED_ERR: " . $e->getMessage(), 500);
    }
}


function validate_keys($expected_keys, $existent_keys) {
    foreach($expected_keys as $key)
        if (!array_key_exists($key, $existent_keys))
            return_err("Existem campos ausentes na requisição");
}
?>