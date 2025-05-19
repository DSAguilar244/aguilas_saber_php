<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["producto_id"])) {
    $id = $_POST["producto_id"];
    
    if (isset($_SESSION["productos"][$id])) {
        $_SESSION["productos"][$id]["nombre"] = $_POST["nombre_producto"];
        $_SESSION["productos"][$id]["estado"] = $_POST["estado_producto"];
        $_SESSION["productos"][$id]["fecha_entrada"] = $_POST["fecha_entrada"];
        $_SESSION["productos"][$id]["fecha_salida"] = $_POST["fecha_salida"];
        $_SESSION["productos"][$id]["cantidad"] = $_POST["cantidad"];
    }
}

header("Location: listado-productos.php");
exit();