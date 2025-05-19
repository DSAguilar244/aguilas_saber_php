<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = isset($_POST["recurso_id"]) ? (int)$_POST["recurso_id"] : -1;

    if ($id >= 0 && isset($_SESSION["recursos"][$id])) {
        array_splice($_SESSION["recursos"], $id, 1);
    }
}

header("Location: estado-recursos.php");
exit;