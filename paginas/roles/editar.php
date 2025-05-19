<?php
session_start();

class Rol {
    public function __construct(public string $nombre, public string $descripcion, public string $estado) {}
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["rol_id"], $_POST["nombre_rol"], $_POST["descripcion_rol"], $_POST["estado_rol"])) {
        $id = (int)$_POST["rol_id"];
        $nombre = trim($_POST["nombre_rol"]);
        $descripcion = trim($_POST["descripcion_rol"]);
        $estado = $_POST["estado_rol"];

        if (isset($_SESSION["roles"][$id])) {
            $_SESSION["roles"][$id] = new Rol($nombre, $descripcion, $estado);
        }
    }

    header("Location: listado-roles.php");
    exit;
}

// Modo GET (mostrar formulario)
if (isset($_GET["rol_id"])) {
    $id = (int)$_GET["rol_id"];
    $rol = $_SESSION["roles"][$id] ?? null;

    if ($rol) {
        ?>
        <form method="POST" action="editar.php">
            <input type="hidden" name="rol_id" value="<?= $id ?>">
            <label>Nombre:</label><input type="text" name="nombre_rol" value="<?= $rol->nombre ?>"><br>
            <label>Descripción:</label><textarea name="descripcion_rol"><?= $rol->descripcion ?></textarea><br>
            <label>Estado:</label>
            <select name="estado_rol">
                <option value="Activo" <?= $rol->estado === "Activo" ? "selected" : "" ?>>Activo</option>
                <option value="Inactivo" <?= $rol->estado === "Inactivo" ? "selected" : "" ?>>Inactivo</option>
            </select><br>
            <button type="submit">Guardar Cambios</button>
        </form>
        <?php
    } else {
        echo "Rol no encontrado.";
    }
} else {
    header("Location: listado-roles.php");
    exit;
}