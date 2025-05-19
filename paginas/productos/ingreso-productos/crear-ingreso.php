<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $fecha = $_POST["fecha"] ?? '';
    $producto = $_POST["producto"] ?? '';
    $cantidad = $_POST["cantidad"] ?? 0;
    $usuario = $_POST["usuario"] ?? '';

    if ($fecha && $producto && $cantidad && $usuario) {
        $nuevoIngreso = [
            "fecha" => $fecha,
            "producto" => $producto,
            "cantidad" => (int)$cantidad,
            "usuario" => $usuario
        ];

        if (!isset($_SESSION["ingresos"])) {
            $_SESSION["ingresos"] = [];
        }

        $_SESSION["ingresos"][] = $nuevoIngreso;
    }
}

header("Location: ingreso-productos.php");
exit;