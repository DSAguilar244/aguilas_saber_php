<?php
session_start();

if (isset($_POST["devolucion_id"], $_SESSION["devoluciones"][$_POST["devolucion_id"]])) {
    $id = (int)$_POST["devolucion_id"];

    $_SESSION["devoluciones"][$id] = [
        "solicitante" => $_POST["solicitante"],
        "tipo" => $_POST["tipo"],
        "articulo" => $_POST["articulo"],
        "fecha_prestamo" => $_POST["fecha_prestamo"],
        "fecha_devolucion" => $_POST["fecha_devolucion"],
        "estado" => $_POST["estado"]
    ];
}

header("Location: devoluciones.php");
exit;