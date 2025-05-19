<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = $_POST["ingreso_id"] ?? -1;
    $fecha = $_POST["fecha"] ?? '';
    $producto = $_POST["producto"] ?? '';
    $cantidad = $_POST["cantidad"] ?? 0;
    $usuario = $_POST["usuario"] ?? '';

    if ($id !== -1 && isset($_SESSION["ingresos"][$id])) {
        $_SESSION["ingresos"][$id] = [
            "fecha" => $fecha,
            "producto" => $producto,
            "cantidad" => (int)$cantidad,
            "usuario" => $usuario
        ];
    }
}

header("Location: ingreso-productos.php");
exit;