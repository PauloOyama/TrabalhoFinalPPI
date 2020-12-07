<?php
include_once '../db.php';
header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    http_response_code(400);
    exit();
}

// Verifica se a chave única necessária existe
if (!array_key_exists("especialidade", $_POST)) {
    http_response_code(400);
    echo json_encode(["ERR_MSG" => "Chave especialdiade ausente"], JSON_UNESCAPED_UNICODE);
    exit();
}
extract($_POST);

$sql = <<<SQL
SELECT nome, medico.codigo
FROM pessoa INNER JOIN funcionario ON pessoa.codigo = funcionario.codigo
            INNER JOIN medico ON funcionario.codigo = medico.codigo
WHERE especialidade = ?
SQL;

$query = $pdo->prepare($sql);
$query->execute([$especialidade]);
$return_arr = [];
while ($row = $query->fetch())
    $return_arr[] = $row;
echo json_encode($return_arr);
exit();
?>