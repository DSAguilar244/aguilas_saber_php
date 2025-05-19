<?php
session_start();

if (isset($_POST["devolucion_id"])) {
    $id = (int)$_POST["devolucion_id"];

    if (isset($_SESSION["devoluciones"][$id])) {
        array_splice($_SESSION["devoluciones"], $id, 1);
    }
}

header("Location: devoluciones.php");
exit;