<?php 

session_start();

// Se já está logado, redireciona para tasks.php
if (isset($_SESSION['user_id'])) {
    header("Location: tasks.php");
    exit;
}

require_once 'db/connect.php';

$message = '';
$validate = false;
$redirect = false;

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $username_or_email = trim($_POST['username_or_email']);
    $password = $_POST['password'];

    if (empty($username_or_email) || empty($password)) {
        $message = "Por favor, preencha todos os campos.";

    } else {
        // Verifica se o usuário existe
        $stmt = $pdo->prepare("SELECT id, username, password FROM users WHERE username = :ue OR email = :ue");
        $stmt->execute(['ue' => $username_or_email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verifica se a senha está correta
        if ($user && password_verify($password, $user['password'])) {
            // Login bem-sucedido
            session_regenerate_id(true); // gera um novo ID de sessão seguro
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];

            $redirect = true;
            $validate = true;

            header("Location: tasks.php");
            exit;

        } else {
            $message = "Nome de usuário/E-mail ou senha incorretos.";
        }
    }
}

?>



<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Login - To-Do List</title>
  <style>
    body { font-family: Arial, sans-serif; margin: 50px; }
    form { max-width: 400px; margin: auto; }
    input { display: block; width: 100%; padding: 10px; margin: 10px 0; }
    button { padding: 10px 20px; }
    .message { text-align: center; margin-bottom: 20px; color: red; }
  </style>
</head>
<body>

<h2>Login</h2>

<?php if ($message): ?>
  <p class="message"><?= $message ?></p>
<?php endif; ?>

<form method="POST" action="">
    <input type="text" name="username_or_email" placeholder="Nome de usuário ou E-mail" required>
    <input type="password" name="password" placeholder="Senha" required>
    <button type="submit">Entrar</button>
</form>

<p>Ainda não tem conta? <a href="register.php">Cadastre-se</a></p>

</body>
</html>