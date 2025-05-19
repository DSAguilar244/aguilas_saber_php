<?php
session_start();

// Asegúrate de que la petición es POST y que los campos están definidos
if ($_SERVER["REQUEST_METHOD"] === "POST" &&
    isset($_POST["nombre_producto"], $_POST["estado_producto"], $_POST["fecha_entrada"], $_POST["fecha_salida"], $_POST["cantidad"])) {

    $nombre = $_POST["nombre_producto"];
    $estado = $_POST["estado_producto"];
    $fecha_entrada = $_POST["fecha_entrada"];
    $fecha_salida = $_POST["fecha_salida"];
    $cantidad = $_POST["cantidad"];

    $producto = [
        "nombre" => $nombre,
        "estado" => $estado,
        "fecha_entrada" => $fecha_entrada,
        "fecha_salida" => $fecha_salida,
        "cantidad" => $cantidad
    ];

    if (!isset($_SESSION["productos"])) {
        $_SESSION["productos"] = [];
    }

    $_SESSION["productos"][] = $producto;
}

// Redirigir solo si no se ha enviado salida antes
header("Location: listado-productos.php");
exit();