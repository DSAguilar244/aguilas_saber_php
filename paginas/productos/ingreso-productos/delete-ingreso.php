<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = $_POST["ingreso_id"] ?? -1;

    if ($id !== -1 && isset($_SESSION["ingresos"][$id])) {
        unset($_SESSION["ingresos"][$id]);
        $_SESSION["ingresos"] = array_values($_SESSION["ingresos"]); // Reindexar
    }
}

header("Location: ingreso-productos.php");
exit;