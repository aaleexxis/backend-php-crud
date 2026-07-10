<?php

session_start();

require_once __DIR__ . "/funciones.php";

if (!estaLogueado()) {
    redirigir("login.php");
}

$usuarioSeguro = e($_SESSION["usuario"] ?? "");
$rolSeguro = e($_SESSION["rol"] ?? "Sin rol");

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

    <?php if (esAdministrador()) { ?>

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