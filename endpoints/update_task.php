<?php 

session_start();

require_once '../db/connect.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

// Atualiar o status da tarefa

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $task_id = $_POST['task_id'];
    $state = $_POST['state'];

    try {

        $stmt = $pdo->prepare("UPDATE tasks SET status = :state WHERE id = :id");
        $stmt->execute([
            'state' => $state,
            'id' => $task_id
        ]);

        echo json_encode(['success' => true]);

    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => "Erro no banco: " . $e->getMessage()]);
    }


}