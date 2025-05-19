<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["producto_id"])) {
    $id = $_POST["producto_id"];

    if (isset($_SESSION["productos"][$id])) {
        unset($_SESSION["productos"][$id]);
        $_SESSION["productos"] = array_values($_SESSION["productos"]); // Reindexar
    }
}

header("Location: listado-productos.php");
exit();