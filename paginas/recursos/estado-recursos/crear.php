<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = trim($_POST["nombre"] ?? "");
    $descripcion = trim($_POST["descripcion"] ?? "");
    $categoria = trim($_POST["categoria"] ?? "");
    $estado = trim($_POST["estado"] ?? "");

    if ($nombre && $descripcion && $categoria && $estado) {
        if (!isset($_SESSION["recursos"])) {
            $_SESSION["recursos"] = [];
        }

        $_SESSION["recursos"][] = [
            "nombre" => $nombre,
            "descripcion" => $descripcion,
            "categoria" => $categoria,
            "estado" => $estado
        ];
    }
}

header("Location: estado-recursos.php");
exit;