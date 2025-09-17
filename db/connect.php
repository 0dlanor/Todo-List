<?php 

// Configuração do banco de dados
$host = 'localhost';
$db_name   = 'todo_list';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host={$host};dbname={$db_name}", $user, $pass);

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    echo "Falha na conexão: " . $e->getMessage();

}

?>