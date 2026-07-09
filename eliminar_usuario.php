<?php

session_start();

require_once __DIR__ . "/conexion.php";

if (!isset($_SESSION["usuario_id"])) {
    header("Location: login.php");
    exit;
}

if (($_SESSION["rol"] ?? "") !== "Administrador") {
    http_response_code(403);
    exit("No tienes permiso para acceder a esta página.");
}

$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

if ($id === false || $id === null || $id < 1) {
    exit("El identificador del usuario no es válido.");
}

if ($id === (int) $_SESSION["usuario_id"]) {
    exit("No puedes eliminar tu propio usuario.");
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
    $eliminacion = $pdo->prepare(
        "DELETE FROM usuarios
         WHERE id = :id"
    );

    $eliminacion->execute([
        "id" => $id
    ]);

    header("Location: usuarios.php");
    exit;
}

$nombreSeguro = htmlspecialchars(
    $usuarioEncontrado["usuario"],
    ENT_QUOTES,
    "UTF-8"
);

$rolSeguro = htmlspecialchars(
    $usuarioEncontrado["rol"],
    ENT_QUOTES,
    "UTF-8"
);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eliminar usuario</title>
</head>
<body>

    <h1>Eliminar usuario</h1>

    <p>
        ¿Seguro que quieres eliminar este usuario?
    </p>

    <p>
        Usuario: <strong><?php echo $nombreSeguro; ?></strong>
    </p>

    <p>
        Rol: <?php echo $rolSeguro; ?>
    </p>

    <form method="POST">
        <button type="submit">Sí, eliminar</button>
    </form>

    <p>
        <a href="usuarios.php">Cancelar</a>
    </p>

</body>
</html>