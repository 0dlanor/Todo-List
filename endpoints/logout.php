<?php 

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {    

    if ($_SESSION['user_id']) {
        // Limpa os dados da sessão
        $_SESSION = [];

        // Remove o cookie de sessão, se existir
        session_destroy();

        echo json_encode(['success' => true]);

    } else {
        echo json_encode(['success' => false, 'message' => 'Não tem usuário logado']);
    }
    
}

?>