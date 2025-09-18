<?php 

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    $user_id = $_SESSION['user_id'];

    echo json_encode(['user_id' => $user_id]);
}

?>