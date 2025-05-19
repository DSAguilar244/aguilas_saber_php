<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST["permiso_id"];
    $usuario = $_POST["usuario"];
    $rol = $_POST["rol"];
    $descripcion = $_POST["descripcion"];
    $asignado_por = $_POST["asignado_por"];
    $fecha = $_POST["fecha"];

    if (isset($_SESSION["permisos"][$id])) {
        $_SESSION["permisos"][$id] = [
            "usuario" => $usuario,
            "rol" => $rol,
            "descripcion" => $descripcion,
            "asignado_por" => $asignado_por,
            "fecha" => $fecha
        ];
    }
}

header("Location: gestion-permisos.php");
exit;