<?php 

session_start();

if (!isset($_SESSION['user_id'])) {
    http_response_code(401); // Unauthorized
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

require_once 'db/connect.php';


$user_id = $_SESSION['user_id'];

// ----- Buscar tarefas do usuário -----

// Prepara e executa a consulta para buscar as tarefas do usuário logado, ordenadas pela data de criação (mais recentes primeiro)
$stmt = $pdo->prepare("SELECT *  FROM tasks WHERE user_id = :user_id ORDER BY created_at DESC");
$stmt->execute(['user_id' => $user_id]);

// Busca todas as tarefas como um array associativo
$tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($tasks);

?>