<?php
session_start();

if (!isset($_SESSION["devoluciones"])) {
    $_SESSION["devoluciones"] = [];
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nuevaDevolucion = [
        "solicitante" => $_POST["solicitante"],
        "tipo" => $_POST["tipo"],
        "articulo" => $_POST["articulo"],
        "fecha_prestamo" => $_POST["fecha_prestamo"],
        "fecha_devolucion" => $_POST["fecha_devolucion"],
        "estado" => $_POST["estado"]
    ];

    $_SESSION["devoluciones"][] = $nuevaDevolucion;
}

header("Location: devoluciones.php");
exit;