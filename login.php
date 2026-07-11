<?php

session_start();

require_once __DIR__ . "/conexion.php";
require_once __DIR__ . "/funciones.php";

if (estaLogueado()) {
    header("Location: panel.php");
    exit;
}

$error = "";
$mensaje = "";
$usuario = "";

if (($_GET["registro"] ?? "") === "correcto") {
    $mensaje = "Registro completado. Ya puedes iniciar sesión.";
}

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

$titulo = "Iniciar sesión";

require_once __DIR__ . "/includes/header.php";

?>

<h1>Iniciar sesión</h1>

<?php if ($error !== "") { ?>
    <p class="alert"><?php echo e($error); ?></p>
<?php } ?>

<?php if ($mensaje !== "") { ?>
    <p class="success"><?php echo e($mensaje); ?></p>
<?php } ?>

<form method="POST">

    <label for="usuario">Usuario:</label>

    <input
        type="text"
        id="usuario"
        name="usuario"
        value="<?php echo e($usuario); ?>"
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

<p>
    ¿No tienes una cuenta?
    <a href="registro.php">Regístrate</a>
</p>

<?php require_once __DIR__ . "/includes/footer.php"; ?>