<?php

require_once __DIR__ . "/conexion.php";

$error = "";
$usuario = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $usuario = trim($_POST["usuario"] ?? "");
    $contrasena = $_POST["contrasena"] ?? "";
    $confirmacion = $_POST["confirmacion"] ?? "";

    if ($usuario === "" || $contrasena === "" || $confirmacion === "") {
        $error = "Debes completar todos los campos.";
    } elseif (strlen($usuario) < 3 || strlen($usuario) > 50) {
        $error = "El usuario debe tener entre 3 y 50 caracteres.";
    } elseif (strlen($contrasena) < 6) {
        $error = "La contraseña debe tener al menos 6 caracteres.";
    } elseif ($contrasena !== $confirmacion) {
        $error = "Las contraseñas no coinciden.";
    } else {
        $consulta = $pdo->prepare(
            "SELECT id
             FROM usuarios
             WHERE usuario = :usuario
             LIMIT 1"
        );

        $consulta->execute([
            "usuario" => $usuario
        ]);

        if ($consulta->fetch()) {
            $error = "Ese nombre de usuario ya está registrado.";
        } else {
            $hash = password_hash(
                $contrasena,
                PASSWORD_DEFAULT
            );

            $insercion = $pdo->prepare(
                "INSERT INTO usuarios (
                    usuario,
                    password_hash,
                    rol
                ) VALUES (
                    :usuario,
                    :password_hash,
                    :rol
                )"
            );

            $insercion->execute([
                "usuario" => $usuario,
                "password_hash" => $hash,
                "rol" => "Usuario"
            ]);

            header("Location: login.php?registro=correcto");
            exit;
        }
    }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear una cuenta</title>
</head>
<body>

    <h1>Crear una cuenta</h1>

    <form method="POST">

        <label for="usuario">Usuario:</label>

        <input
            type="text"
            id="usuario"
            name="usuario"
            value="<?php echo htmlspecialchars(
                $usuario,
                ENT_QUOTES,
                "UTF-8"
            ); ?>"
            minlength="3"
            maxlength="50"
            required
        >

        <br><br>

        <label for="contrasena">Contraseña:</label>

        <input
            type="password"
            id="contrasena"
            name="contrasena"
            minlength="6"
            required
        >

        <br><br>

        <label for="confirmacion">Repite la contraseña:</label>

        <input
            type="password"
            id="confirmacion"
            name="confirmacion"
            minlength="6"
            required
        >

        <br><br>

        <button type="submit">Registrarse</button>

    </form>

    <?php if ($error !== "") { ?>
        <p>
            <?php echo htmlspecialchars(
                $error,
                ENT_QUOTES,
                "UTF-8"
            ); ?>
        </p>
    <?php } ?>

    <p>
        ¿Ya tienes una cuenta?
        <a href="login.php">Inicia sesión</a>
    </p>

</body>
</html>