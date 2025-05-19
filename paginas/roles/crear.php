<?php
session_start();

class Rol {
    public function __construct(public string $nombre, public string $descripcion, public string $estado) {}
}

// Validar y recoger los datos
if (isset($_POST["nombre_rol"], $_POST["descripcion_rol"], $_POST["estado_rol"])) {
    $nombre = trim($_POST["nombre_rol"]);
    $descripcion = trim($_POST["descripcion_rol"]);
    $estado = $_POST["estado_rol"];

    $nuevoRol = new Rol($nombre, $descripcion, $estado);

    if (!isset($_SESSION["roles"])) {
        $_SESSION["roles"] = [];
    }

    $_SESSION["roles"][] = $nuevoRol;
}

// Redirigir de nuevo al listado
header("Location: listado-roles.php");
exit;
