<?php
session_start();

if (!isset($_SESSION["recursos"])) {
    $_SESSION["recursos"] = [];
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = $_POST['recurso_id'] ?? null;

    if ($id !== null && isset($_SESSION["recursos"][$id])) {
        unset($_SESSION["recursos"][$id]);
        // Reindexar el array para evitar huecos en las claves
        $_SESSION["recursos"] = array_values($_SESSION["recursos"]);
    }
}

header("Location: recursos.php");