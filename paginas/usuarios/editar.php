<?php
session_start();

if (isset($_POST["usuario_id"])) {
    $id = (int)$_POST["usuario_id"];

    if (isset($_SESSION["usuarios"][$id])) {
        $_SESSION["usuarios"][$id] = [
            "nombre" => $_POST["nombre"] ?? "",
            "apellido" => $_POST["apellido"] ?? "",
            "correo" => $_POST["correo"] ?? "",
            "telefono" => $_POST["telefono"] ?? "",
            "rol" => $_POST["rol"] ?? "",
            "estado" => $_POST["estado"] ?? "Activo"
        ];
    }
}

header("Location: listado-usuarios.php");
exit;