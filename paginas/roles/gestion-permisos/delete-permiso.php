<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST["permiso_id"];
    
    if (isset($_SESSION["permisos"][$id])) {
        unset($_SESSION["permisos"][$id]);
        $_SESSION["permisos"] = array_values($_SESSION["permisos"]); // Reindexar
    }
}

header("Location: gestion-permisos.php");
exit;