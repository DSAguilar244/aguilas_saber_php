<?php
session_start();

if (!isset($_SESSION["recursos"])) {
    $_SESSION["recursos"] = [];
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = $_POST['nombre'] ?? '';
    $descripcion = $_POST['descripcion'] ?? '';
    $categoria = $_POST['categoria'] ?? '';
    $estado = $_POST['estado'] ?? '';

    if ($nombre && $descripcion && $categoria && $estado) {
        $_SESSION["recursos"][] = [
            'nombre' => $nombre,
            'descripcion' => $descripcion,
            'categoria' => $categoria,
            'estado' => $estado,
        ];
    }
}

header("Location: recursos.php");
exit;