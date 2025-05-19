<?php
session_start();

if (!isset($_SESSION["usuarios"])) {
    $_SESSION["usuarios"] = [];
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nuevoUsuario = [
        "nombre" => $_POST["nombre"] ?? "",
        "apellido" => $_POST["apellido"] ?? "",
        "correo" => $_POST["correo"] ?? "",
        "telefono" => $_POST["telefono"] ?? "",
        "rol" => $_POST["rol"] ?? "",
        "estado" => $_POST["estado"] ?? "Activo"
    ];

    $_SESSION["usuarios"][] = $nuevoUsuario;
}

header("Location: listado-usuarios.php");
exit;