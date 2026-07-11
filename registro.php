<?php

session_start();

require_once __DIR__ . "/conexion.php";
require_once __DIR__ . "/funciones.php";

if (estaLogueado()) {
    header("Location: panel.php");
    exit;
}

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
            $hash = password_hash($contrasena, PASSWORD_DEFAULT);

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

$titulo = "Crear cuenta";

require_once __DIR__ . "/includes/header.php";

?>

<h1>Crear cuenta</h1>

<?php if ($error !== "") { ?>
    <p class="alert"><?php echo e($error); ?></p>
<?php } ?>

<form method="POST">

    <label for="usuario">Usuario:</label>

    <input
        type="text"
        id="usuario"
        name="usuario"
        value="<?php echo e($usuario); ?>"
        minlength="3"
        maxlength="50"
        autocomplete="username"
        required
    >

    <br><br>

    <label for="contrasena">Contraseña:</label>

    <input
        type="password"
        id="contrasena"
        name="contrasena"
        minlength="6"
        autocomplete="new-password"
        required
    >

    <br><br>

    <label for="confirmacion">Repite la contraseña:</label>

    <input
        type="password"
        id="confirmacion"
        name="confirmacion"
        minlength="6"
        autocomplete="new-password"
        required
    >

    <br><br>

    <button type="submit">Registrarse</button>

</form>

<p>
    ¿Ya tienes una cuenta?
    <a href="login.php">Inicia sesión</a>
</p>

<?php require_once __DIR__ . "/includes/footer.php"; ?>