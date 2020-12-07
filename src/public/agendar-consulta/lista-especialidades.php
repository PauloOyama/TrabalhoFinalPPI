<?php
include_once '../db.php';
header("Content-Type: application/json; charset=UTF-8");

$sql = <<<SQL
SELECT especialidade
FROM medico
SQL;

$res = $pdo->query($sql);
$return_arr = [];
while ($row = $res->fetch())
    $return_arr[] = $row["especialidade"];
echo json_encode($return_arr)
?>