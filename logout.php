<?php 

session_start();

// Limpa os dados da sessão
$_SESSION = [];

// Remove o cookie de sessão, se existir
session_destroy();

header("Location: index.php");
exit;

?>