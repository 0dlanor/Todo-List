<?php 

session_start();

require_once '../db/connect.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

// Adicionar nova tarefa

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $due_date = !empty($_POST['due_date']) ? $_POST['due_date'] : null;
    $status = $_POST['status'];
    $user_id = $_SESSION['user_id'];

    try {
        $stmt = $pdo->prepare("INSERT INTO tasks (user_id, title, status, due_date) VALUES (:user_id, :title, :status, :due_date)");
        $stmt->execute([
            'user_id' => $user_id,
            'title' => $title,
            'status' => $status,
            'due_date' => $due_date
        ]);

        $task_id = $pdo->lastInsertId();

        echo json_encode([
            'success' => true,
            'task' => [
                'task_id' => $task_id,
                'title' => $title,
                'status' => $status,
                'due_date' => $due_date,
            ]
        ]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => "Erro no banco: " . $e->getMessage()]);
    }
}

?>