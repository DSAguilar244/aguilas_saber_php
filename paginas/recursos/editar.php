<?php
session_start();

if (!isset($_SESSION["recursos"])) {
    $_SESSION["recursos"] = [];
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = $_POST['recurso_id'] ?? null;
    $nombre = $_POST['nombre'] ?? '';
    $descripcion = $_POST['descripcion'] ?? '';
    $categoria = $_POST['categoria'] ?? '';
    $estado = $_POST['estado'] ?? '';

    if ($id !== null && isset($_SESSION["recursos"][$id])) {
        $_SESSION["recursos"][$id] = [
            'nombre' => $nombre,
            'descripcion' => $descripcion,
            'categoria' => $categoria,
            'estado' => $estado,
        ];
    }
}

header("Location: recursos.php");
exit;