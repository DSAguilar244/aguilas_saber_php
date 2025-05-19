<?php
session_start();

if (isset($_POST["prestamo_id"]) && isset($_SESSION["prestamos"][$_POST["prestamo_id"]])) {
    $id = $_POST["prestamo_id"];
    $_SESSION["prestamos"][$id] = [
        "solicitante" => $_POST["solicitante"],
        "tipo" => $_POST["tipo"],
        "articulo" => $_POST["articulo"],
        "fecha_prestamo" => $_POST["fecha_prestamo"],
        "fecha_devolucion" => $_POST["fecha_devolucion"],
        "estado" => $_POST["estado"]
    ];
}

header("Location: prestamos.php");
exit;