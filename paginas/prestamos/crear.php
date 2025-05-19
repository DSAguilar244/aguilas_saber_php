<?php
session_start();

if (!isset($_SESSION["prestamos"])) {
    $_SESSION["prestamos"] = [];
}

$nuevo = [
    "solicitante" => $_POST["solicitante"],
    "tipo" => $_POST["tipo"],
    "articulo" => $_POST["articulo"],
    "fecha_prestamo" => $_POST["fecha_prestamo"],
    "fecha_devolucion" => $_POST["fecha_devolucion"],
    "estado" => $_POST["estado"]
];

$_SESSION["prestamos"][] = $nuevo;

header("Location: prestamos.php");
exit;