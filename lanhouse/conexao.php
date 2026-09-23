<?php
function obterConexao() {
    $host = 'localhost';
    $db = 'apiLanHouse';
    $user = 'root';
    $pass = '';

    try {
        return new PDO(
            "mysql:host=$host;dbname=$db;charset=utf8",
            $user,
            $pass,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]
        );

    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode([
            'erro' => 'Erro ao conectar ao banco de dados'
        ]);
        exit;
    }
}
?>