<?php

session_start();

require_once __DIR__ . "/conexion.php";
require_once __DIR__ . "/funciones.php";

if (!estaLogueado()) {
    header("Location: login.php");
    exit;
}

if (!esAdministrador()) {
    http_response_code(403);
    exit("No tienes permiso para acceder a esta página.");
}

$error = "";
$mensaje = "";

$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

if ($id === false || $id === null || $id < 1) {
    exit("El identificador del usuario no es válido.");
}

$consulta = $pdo->prepare(
    "SELECT id, usuario, rol
     FROM usuarios
     WHERE id = :id
     LIMIT 1"
);

$consulta->execute([
    "id" => $id
]);

$usuarioEncontrado = $consulta->fetch();

if (!$usuarioEncontrado) {
    exit("El usuario no existe.");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $csrfToken = $_POST["csrf_token"] ?? "";
    $nuevoRol = $_POST["rol"] ?? "";

    $rolesPermitidos = [
        "Usuario",
        "Administrador"
    ];

    if (!verificarTokenCsrf($csrfToken)) {
        $error = "Solicitud no válida.";
    } elseif (!in_array($nuevoRol, $rolesPermitidos, true)) {
        $error = "El rol seleccionado no es válido.";
    } elseif ($id === (int) $_SESSION["usuario_id"]) {
        $error = "No puedes cambiar tu propio rol.";
    } else {
        $actualizacion = $pdo->prepare(
            "UPDATE usuarios
             SET rol = :rol
             WHERE id = :id"
        );

        $actualizacion->execute([
            "rol" => $nuevoRol,
            "id" => $id
        ]);

        $usuarioEncontrado["rol"] = $nuevoRol;
        $mensaje = "El rol se ha actualizado correctamente.";
    }
}

$nombreSeguro = e($usuarioEncontrado["usuario"]);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar usuario</title>
</head>

<body>

    <h1>Editar usuario</h1>

    <p>
        Usuario: <strong><?php echo $nombreSeguro; ?></strong>
    </p>

    <?php if ($error !== "") { ?>
        <p><?php echo e($error); ?></p>
    <?php } ?>

    <?php if ($mensaje !== "") { ?>
        <p><?php echo e($mensaje); ?></p>
    <?php } ?>

    <form method="POST">

        <input
            type="hidden"
            name="csrf_token"
            value="<?php echo e(generarTokenCsrf()); ?>"
        >

        <label for="rol">Rol:</label>

        <select id="rol" name="rol" required>

            <option
                value="Usuario"
                <?php if ($usuarioEncontrado["rol"] === "Usuario") { echo "selected"; } ?>
            >
                Usuario
            </option>

            <option
                value="Administrador"
                <?php if ($usuarioEncontrado["rol"] === "Administrador") { echo "selected"; } ?>
            >
                Administrador
            </option>

        </select>

        <button type="submit">Guardar cambios</button>

    </form>

    <p>
        <a href="usuarios.php">Volver al listado</a>
    </p>

</body>
</html>