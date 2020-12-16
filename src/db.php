<?php
$db_host = "fdb30.awardspace.net";
$db_name = "3630592_bdtrabalho6";
$db_username = "3630592_bdtrabalho6";
$db_password = "trabalho6";

$dsn = "mysql:host=$db_host; dbname=$db_name; charset=utf8mb4";
$options = [
    PDO::ATTR_EMULATE_PREPARES => false,
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $db_username, $db_password, $options);
} catch (Exception $e) {
    $msg = $e->getMessage();
    http_response_code(500);
    echo json_encode(["ERR_MSG" => $msg], JSON_UNESCAPED_UNICODE);
    exit();
}
