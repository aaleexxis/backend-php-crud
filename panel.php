<?php

session_start();

if (!isset($_SESSION["usuario"])) {
    header("Location: login.php");
    exit;
}

$usuarioSeguro = htmlspecialchars($_SESSION["usuario"],ENT_QUOTES,"UTF-8");
$rol = htmlspecialchars($_SESSION["rol"] ?? "sin rol", ENT_QUOTES,"UTF-8");

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel privado</title>
</head>
<body>

    <h1>Panel privado</h1>

    <p>Bienvenido, <?php echo $usuarioSeguro; ?>.</p>

    <p>Has iniciado sesión correctamente.</p>

    <p><?php echo "Tu rol es: $rol"; ?>.</p>

    <a href="logout.php">Cerrar sesión</a>

</body>
</html>