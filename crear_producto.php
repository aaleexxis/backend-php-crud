<?php

session_start();

require_once __DIR__ . "/conexion.php";
require_once __DIR__ . "/funciones.php";

requerirAdministrador();

$error = "";

$nombre = "";
$descripcion = "";
$precio = "";
$stock = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $csrfToken = $_POST["csrf_token"] ?? "";

    $nombre = trim($_POST["nombre"] ?? "");
    $descripcion = trim($_POST["descripcion"] ?? "");
    $precio = trim($_POST["precio"] ?? "");
    $stock = trim($_POST["stock"] ?? "");

    if (!verificarTokenCsrf($csrfToken)) {
        $error = "Solicitud no válida.";
    } elseif ($nombre === "") {
        $error = "El nombre del producto es obligatorio.";
    } elseif (!is_numeric($precio) || $precio < 0) {
        $error = "El precio debe ser un número válido.";
    } elseif (!filter_var($stock, FILTER_VALIDATE_INT) && $stock !== "0") {
        $error = "El stock debe ser un número entero.";
    } elseif ((int) $stock < 0) {
        $error = "El stock no puede ser negativo.";
    } else {
        $insercion = $pdo->prepare(
            "INSERT INTO productos (
                nombre,
                descripcion,
                precio,
                stock
            ) VALUES (
                :nombre,
                :descripcion,
                :precio,
                :stock
            )"
        );

        $insercion->execute([
            "nombre" => $nombre,
            "descripcion" => $descripcion,
            "precio" => $precio,
            "stock" => $stock
        ]);

        header("Location: productos.php");
        exit;
    }
}

$titulo = "Crear producto";

require_once __DIR__ . "/includes/header.php";
require_once __DIR__ . "/includes/nav.php";

?>

<h1>Crear producto</h1>

<p>
    <a href="productos.php">Volver a productos</a>
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

    <label for="nombre">Nombre:</label>

    <input
        type="text"
        id="nombre"
        name="nombre"
        value="<?php echo e($nombre); ?>"
        required
    >

    <br><br>

    <label for="descripcion">Descripción:</label>

    <textarea
        id="descripcion"
        name="descripcion"
    ><?php echo e($descripcion); ?></textarea>

    <br><br>

    <label for="precio">Precio:</label>

    <input
        type="number"
        id="precio"
        name="precio"
        step="0.01"
        min="0"
        value="<?php echo e($precio); ?>"
        required
    >

    <br><br>

    <label for="stock">Stock:</label>

    <input
        type="number"
        id="stock"
        name="stock"
        min="0"
        value="<?php echo e($stock); ?>"
        required
    >

    <br><br>

    <button type="submit">Guardar producto</button>

</form>

<?php require_once __DIR__ . "/includes/footer.php"; ?>