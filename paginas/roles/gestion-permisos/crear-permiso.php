<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST["usuario"];
    $rol = $_POST["rol"];
    $descripcion = $_POST["descripcion"];
    $asignado_por = $_POST["asignado_por"];
    $fecha = $_POST["fecha"];

    $nuevoPermiso = [
        "usuario" => $usuario,
        "rol" => $rol,
        "descripcion" => $descripcion,
        "asignado_por" => $asignado_por,
        "fecha" => $fecha
    ];

    $_SESSION["permisos"][] = $nuevoPermiso;
}

header("Location: gestion-permisos.php");
exit;