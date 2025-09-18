<?php 

session_start();

require_once '../db/connect.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

// Deletar uma tarefa

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $task_id = $_POST['task_id'];

    try {
        $stmt = $pdo->prepare("DELETE FROM tasks WHERE id = :id");
        $stmt->execute(['id' => $task_id]);

        echo json_encode(['success' => true]);
        
    } catch (PDOException $e) {

        echo json_encode(['success' => false, 'message' => "Erro no banco: " . $e->getMessage()]);

    }

}

?>