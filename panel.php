<?php

session_start();

if (!isset($_SESSION["usuario_id"])) {
    header("Location: login.php");
    exit;
}

$usuarioSeguro = htmlspecialchars(
    $_SESSION["usuario"],
    ENT_QUOTES,
    "UTF-8"
);

$rolSeguro = htmlspecialchars(
    $_SESSION["rol"] ?? "Sin rol",
    ENT_QUOTES,
    "UTF-8"
);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel privado</title>
</head>
<body>

    <h1>Panel privado</h1>

    <p>
        Bienvenido, <?php echo $usuarioSeguro; ?>.
    </p>

    <p>Has iniciado sesión correctamente.</p>

    <p>
        Tu rol es: <?php echo $rolSeguro; ?>.
    </p>

    <?php if (($_SESSION["rol"] ?? "") === "Administrador") { ?>

        <p>
            <a href="usuarios.php">Gestionar usuarios</a>
        </p>

        <p>
            <a href="productos.php">Gestionar productos</a>
        </p>

    <?php } ?>

    <a href="logout.php">Cerrar sesión</a>

</body>
</html>