<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = isset($_POST["recurso_id"]) ? (int)$_POST["recurso_id"] : -1;
    $nombre = trim($_POST["nombre"] ?? "");
    $descripcion = trim($_POST["descripcion"] ?? "");
    $categoria = trim($_POST["categoria"] ?? "");
    $estado = trim($_POST["estado"] ?? "");

    if ($id >= 0 && isset($_SESSION["recursos"][$id]) && $nombre && $descripcion && $categoria && $estado) {
        $_SESSION["recursos"][$id] = [
            "nombre" => $nombre,
            "descripcion" => $descripcion,
            "categoria" => $categoria,
            "estado" => $estado
        ];
    }
}

header("Location: estado-recursos.php");
exit;