<?php
include_once '../../db.php';
include_once '../../common.php';
header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    http_response_code(400);
    exit();
}

validate_keys(["especialidade"], $_POST);
extract($_POST);

$sql = <<<SQL
SELECT nome, medico.codigo
FROM pessoa INNER JOIN funcionario ON pessoa.codigo = funcionario.codigo
            INNER JOIN medico ON funcionario.codigo = medico.codigo
WHERE especialidade = ?
SQL;

$query = run_sql($pdo, $sql, [$especialidade]);

// Um loop é utilizado para evitar o consumo de memória do fetchAll()
$return_arr = []; 
while ($row = $query->fetch())
    $return_arr[] = $row;
echo json_encode($return_arr);
?>