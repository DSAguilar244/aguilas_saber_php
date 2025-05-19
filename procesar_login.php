<?php
$usuario = $_POST['usuario'] ?? '';
$contraseña = $_POST['contraseña'] ?? '';

if (empty($usuario) || empty($contraseña)) {
    header("Location: index.php?mensaje=Por+favor,+completa+todos+los+campos.&tipo=warning");
    exit;
}

if (strlen($contraseña) < 6) {
    header("Location: index.php?mensaje=La+contraseña+debe+tener+al+menos+6+caracteres.&tipo=warning");
    exit;
}

// Simulación de login exitoso
if ($usuario === 'admin' && $contraseña === '123456') {
    header("Location: paginas/pagina_inicio/home.php");
    exit;
} else {
    header("Location: index.php?mensaje=Usuario+o+contraseña+incorrectos.&tipo=danger");
    exit;
}