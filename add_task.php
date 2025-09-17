<?php 

session_start();

require_once 'db/connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

// Adicionar nova tarefa

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $due_date = !empty($_POST['due_date']) ? $_POST['due_date'] : null;
    $user_id = $_SESSION['user_id'];

    $stmt = $pdo->prepare("INSERT INTO tasks (user_id, title, due_date) VALUES (:user_id, :title, :due_date)");
    $stmt->execute([
        'user_id' => $user_id,
        'title' => $title,
        'due_date' => $due_date
    ]);

    echo json_encode(['success' => true]);

}

?>