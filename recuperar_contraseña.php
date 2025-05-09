<?php
$email = $_POST['email'] ?? '';

if (empty($email)) {
    header("Location: index.php?mensaje=Por+favor,+ingresa+tu+correo+electrónico.&tipo=warning");
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header("Location: index.php?mensaje=Correo+electrónico+inválido.&tipo=warning");
    exit;
}

// Simula envío de correo de recuperación
header("Location: index.php?mensaje=Se+ha+enviado+un+correo+de+recuperación.&tipo=success");
exit;
