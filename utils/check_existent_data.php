<?php 

require_once '../db/connect.php';

header('Content-Type: application/json');

$result = ['username' => null, 'email' => null];

if (isset($_GET['username']) && isset($_GET['email'])) {
    $username = trim($_GET['username']);
    $email = trim($_GET['email']);

    // Verifica username

    if ($username) {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = :username");
        $stmt->execute(['username' => $username]);
        $result['username'] = $stmt->rowCount() > 0 ? 'existente' : 'disponivel';
    }

    // Verifica email
    if ($email) {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $result['email'] = $stmt->rowCount() > 0 ? 'existente' : 'disponivel';
    }


    
    echo json_encode($result);
}

?>