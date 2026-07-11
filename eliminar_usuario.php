<?php

session_start();

require_once __DIR__ . "/conexion.php";
require_once __DIR__ . "/funciones.php";

requerirAdministrador();

$error = "";

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
    $csrfToken = $_POST["csrf_token"] ?? "";

    if (!verificarTokenCsrf($csrfToken)) {
        $error = "Solicitud no válida.";
    } else {
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
}

$nombreSeguro = e($usuarioEncontrado["usuario"]);
$rolSeguro = e($usuarioEncontrado["rol"]);

$titulo = "Eliminar usuario";

require_once __DIR__ . "/includes/header.php";
require_once __DIR__ . "/includes/nav.php";

?>

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

<?php if ($error !== "") { ?>
    <p><?php echo e($error); ?></p>
<?php } ?>

<form method="POST">

    <input
        type="hidden"
        name="csrf_token"
        value="<?php echo e(generarTokenCsrf()); ?>"
    >

    <button type="submit">Sí, eliminar</button>

</form>

<p>
    <a href="usuarios.php">Cancelar</a>
</p>

<?php require_once __DIR__ . "/includes/footer.php"; ?>