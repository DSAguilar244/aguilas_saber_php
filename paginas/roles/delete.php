<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["rol_id"])) {
    $id = (int)$_POST["rol_id"];

    if (isset($_SESSION["roles"][$id])) {
        unset($_SESSION["roles"][$id]);
        $_SESSION["roles"] = array_values($_SESSION["roles"]); // Reindexar el array
    }
}

header("Location: listado-roles.php");
exit;