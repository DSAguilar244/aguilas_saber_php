<?php
session_start();

if (isset($_POST["usuario_id"])) {
    $id = (int)$_POST["usuario_id"];

    if (isset($_SESSION["usuarios"][$id])) {
        array_splice($_SESSION["usuarios"], $id, 1); // Eliminar sin dejar huecos
    }
}

header("Location: listado-usuarios.php");
exit;