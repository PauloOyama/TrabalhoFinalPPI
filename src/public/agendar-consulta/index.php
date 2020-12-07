<?php
include_once "../../db.php";
include_once "../../common.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $expected_keys = ["data_agendamento", "horario", "nome", "email", "telefone", "id_medico"];
    validate_keys($expected_keys, $_POST);
    
    extract($_POST);
    
    $sql = <<<SQL
    INSERT INTO agenda (data_agendamento, horario, nome, email, telefone, codigo_medico)
    VALUES (?, ?, ?, ?, ?, ?)
    SQL;
    run_sql($pdo, $sql, [$data_agendamento, $horario, $nome, $email, $telefone, $id_medico]);
}
?>