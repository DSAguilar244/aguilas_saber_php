<?php
session_start();

if (isset($_POST["prestamo_id"]) && isset($_SESSION["prestamos"][$_POST["prestamo_id"]])) {
    $id = $_POST["prestamo_id"];
    array_splice($_SESSION["prestamos"], $id, 1);
}

header("Location: prestamos.php");
exit;