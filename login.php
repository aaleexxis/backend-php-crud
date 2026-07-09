<?php

session_start();

require_once __DIR__ . "/conexion.php";

$error = "";
$mensaje = "";
$usuario = "";

// Mensaje recibido después de completar el registro.
if (($_GET["registro"] ?? "") === "correcto") {
    $mensaje = "Registro completado. Ya puedes iniciar sesión.";
}

// Procesar el formulario.
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $usuario = trim($_POST["usuario"] ?? "");
    $contrasena = $_POST["contrasena"] ?? "";

    if ($usuario === "" || $contrasena === "") {
        $error = "Debes completar todos los campos.";
    } else {
        $consulta = $pdo->prepare(
            "SELECT id, usuario, password_hash, rol
             FROM usuarios
             WHERE usuario = :usuario
             LIMIT 1"
        );

        $consulta->execute([
            "usuario" => $usuario
        ]);

        $usuarioEncontrado = $consulta->fetch();

        if (
            $usuarioEncontrado &&
            password_verify(
                $contrasena,
                $usuarioEncontrado["password_hash"]
            )
        ) {
            // Cambia el identificador de sesión después del login.
            session_regenerate_id(true);

            $_SESSION["usuario_id"] = $usuarioEncontrado["id"];
            $_SESSION["usuario"] = $usuarioEncontrado["usuario"];
            $_SESSION["rol"] = $usuarioEncontrado["rol"];

            header("Location: panel.php");
            exit;
        }

        $error = "Usuario o contraseña incorrectos.";
    }
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Inicio de sesión</title>
</head>

<body>

    <h1>Iniciar sesión</h1>

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
            autocomplete="username"
            required
        >

        <br><br>

        <label for="contrasena">Contraseña:</label>

        <input
            type="password"
            id="contrasena"
            name="contrasena"
            autocomplete="current-password"
            required
        >

        <br><br>

        <button type="submit">Entrar</button>

    </form>

    <?php if ($error !== "") { ?>
        <p>
            <?php
            echo htmlspecialchars(
                $error,
                ENT_QUOTES,
                "UTF-8"
            );
            ?>
        </p>
    <?php } ?>

    <?php if ($mensaje !== "") { ?>
        <p>
            <?php
            echo htmlspecialchars(
                $mensaje,
                ENT_QUOTES,
                "UTF-8"
            );
            ?>
        </p>
    <?php } ?>

    <p>
        ¿No tienes una cuenta?
        <a href="registro.php">Regístrate</a>
    </p>

</body>
</html>