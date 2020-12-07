<?php
include_once "../../db.php";
include_once "../../common.php";
header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    http_response_code(400);
    exit();
}

validate_keys(["id_medico", "data"], $_POST);
extract($_POST);

$sql = <<<SQL
SELECT horario
FROM agenda
WHERE codigo_medico = ? AND data_agendamento = ?
SQL;

$query = run_sql($pdo, $sql, [$id_medico, $data]);
$return_arr = [];
// Um loop é utilizado para evitar o consumo de memória do fetchAll()
$return_arr = []; 
while ($row = $query->fetch())
    $return_arr[] = $row["horario"];
echo json_encode($return_arr);
?>