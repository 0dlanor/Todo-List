<?php

session_start();
require 'db/connect.php';

$message = '';
$redirect = false;

// Envio do formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validação básica
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $message = 'Por favor, preencha todos os campos.';

    } else if ($password !== $confirm_password) {
        $message = 'As senhas não coincidem.';

    } else {

        // Verificar se o usuário já existe

        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = :username OR email = :email");
        $exec = $stmt->execute([
            'username' => $username,
            'email' => $email
        ]);

        // Se retornar mais 0 colunas na consulta, significa que o usuário já existe
        if ($stmt->rowCount() > 0) {
            $message = "Nome de usuário ou email já estão em uso.";

        } else {
            $password_hash = password_hash($password, PASSWORD_BCRYPT);

            $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
            $stmt->execute([
                'username' => $username,
                'email' => $email,
                'password' => $password_hash
            ]);

            if ($stmt->rowCount() > 0) {
                $message = "Registro bem-sucedido! Você já pode fazer login.";

                
                // Login automático
                session_regenerate_id(true); // gera um novo ID de sessão seguro
                $_SESSION['user_id'] = $pdo->lastInsertId();
                $_SESSION['username'] = $username;

                $message = "Cadastro realizado com sucesso! Redirecionando para a página inicial...";
                $redirect = true;

            } else {
                $message = "Erro ao registrar. Por favor, tente novamente.";
            }

            
        }
    }

}

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title>Cadastro - To-Do List</title>

<style>
    body { font-family: Arial, sans-serif; margin: 50px; }

    form { max-width: 400px; margin: auto; }

    input { display: block; width: 100%; padding: 10px; margin: 10px 0; }

    button { padding: 10px 20px; }

    .message { text-align: center; margin-bottom: 20px; }

    .feedback-msg { color: red; font-size: 0.9em; margin-top: -5px; margin-bottom: 10px; }

</style>

</head>

<body>

<h2>Cadastro de Usuário</h2>

<?php if ($message) echo "<p class='message'>{$message}</p>"; ?>


<?php if (!$redirect) : ?>
    <form method="POST" action="">
        <label for="username" class="feedback-msg" id="username-msg"></label>
        <input type="text" id="username" name="username" placeholder="Nome de usuário" required>

        <label for="email" class="feedback-msg" id="email-msg"></label>
        <input type="email" id="email" name="email" placeholder="E-mail" required>

        <label for="password" class="feedback-msg"></label>
        <input type="password" id="password" name="password" placeholder="Senha" required>

        <label for="confirm_password" class="feedback-msg"></label>
        <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirme a senha" required>

        <button type="submit">Cadastrar</button>

    </form>
    <p>Já tem cadastro? <a href="index.php">Faça login</a></p>
<?php endif; ?>

<?php if ($redirect) : ?>
    <script>
    setTimeout(() => {
        window.location.href = 'index.php';
    }, 3000); // 3000ms = 3 segundos
    </script>
<?php endif; ?>

<script>

// Validação visual no frontend

const usernameInput = document.querySelector('#username');
const emailInput = document.querySelector('#email');

const usernameMsg = document.getElementById('username-msg');
const emailMsg = document.getElementById('email-msg');


async function checkAvailability() {
    
    console.log('Verificando disponibilidade...');

    const username = usernameInput.value.trim();
    const email = emailInput.value.trim();

    if (!username && !email) {
        usernameMsg.textContent = '';
        emailMsg.textContent = '';
        return;
    };

    try {
        const response = await fetch(`utils/check_existent_data.php?username=${encodeURIComponent(username)}&email=${encodeURIComponent(email)}`);
        const data = await response.json();

        // data = { username = "existente", email = "disponivel" }

        console.log(data); // Apenas para depuração


        // Verificação do username

        if (data.username === 'existente') {
            usernameMsg.textContent = 'Nome de usuário já está em uso.';

            usernameInput.style.borderColor = 'red';
            usernameMsg.style.color = 'red';
            
        } else if (data.username === 'disponivel') {
            usernameMsg.textContent = 'Nome de usuário disponível.';

            usernameInput.style.borderColor = 'green';
            usernameMsg.style.color = 'green';

        } else {
            usernameMsg.textContent = '';
            usernameInput.style.borderColor = '';
        }

        // Verificação do email

        if (data.email === 'existente') {
            emailMsg.textContent = 'Email já está em uso.';

            emailInput.style.borderColor = 'red';
            emailMsg.style.color = 'red';

        } else if (data.email === 'disponivel') {
            emailMsg.textContent = 'Email disponível.';

            emailInput.style.borderColor = 'green';
            emailMsg.style.color = 'green';

        } else {
            emailMsg.textContent = '';
            emailInput.style.borderColor = '';
        }

    } catch (error) {
        console.error('Erro na verificação:', error);
    }

}

// Chama a funcção quando o usuário sai do campo (blur)
usernameInput.addEventListener('blur', checkAvailability);
emailInput.addEventListener('blur', checkAvailability);

</script>

</body>


</html>