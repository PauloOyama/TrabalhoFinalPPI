<?php
header('Content-Type: application/json');

include_once "../../db.php";
include_once "../../common.php";

validate_keys(["cep"], $_GET);
$cep = $_GET["cep"];

$sql = <<<SQL
SELECT * FROM base_enderecos_ajax
WHERE cep = ?
SQL;

$query = run_sql($pdo, $sql, [$cep]);
$row = $query->fetch();
if ($row)
    echo json_encode($row);
else
    return_err("CEP not found", 404);
?>