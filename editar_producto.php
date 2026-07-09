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

$error = "";
$mensaje = "";

$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

if ($id === false || $id === null || $id < 1) {
    exit("El identificador del producto no es válido.");
}

$consulta = $pdo->prepare(
    "SELECT id, nombre, descripcion, precio, stock
     FROM productos
     WHERE id = :id
     LIMIT 1"
);

$consulta->execute([
    "id" => $id
]);

$producto = $consulta->fetch();

if (!$producto) {
    exit("El producto no existe.");
}

$nombre = $producto["nombre"];
$descripcion = $producto["descripcion"];
$precio = $producto["precio"];
$stock = $producto["stock"];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = trim($_POST["nombre"] ?? "");
    $descripcion = trim($_POST["descripcion"] ?? "");
    $precio = trim($_POST["precio"] ?? "");
    $stock = trim($_POST["stock"] ?? "");

    if ($nombre === "") {
        $error = "El nombre del producto es obligatorio.";
    } elseif (!is_numeric($precio) || $precio < 0) {
        $error = "El precio debe ser un número válido.";
    } elseif (!filter_var($stock, FILTER_VALIDATE_INT) && $stock !== "0") {
        $error = "El stock debe ser un número entero.";
    } elseif ((int) $stock < 0) {
        $error = "El stock no puede ser negativo.";
    } else {
        $actualizacion = $pdo->prepare(
            "UPDATE productos
             SET nombre = :nombre,
                 descripcion = :descripcion,
                 precio = :precio,
                 stock = :stock
             WHERE id = :id"
        );

        $actualizacion->execute([
            "nombre" => $nombre,
            "descripcion" => $descripcion,
            "precio" => $precio,
            "stock" => $stock,
            "id" => $id
        ]);

        $mensaje = "El producto se ha actualizado correctamente.";
    }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar producto</title>
</head>
<body>

    <h1>Editar producto</h1>

    <p>
        <a href="productos.php">Volver a productos</a>
    </p>

    <?php if ($error !== "") { ?>
        <p>
            <?php echo htmlspecialchars($error, ENT_QUOTES, "UTF-8"); ?>
        </p>
    <?php } ?>

    <?php if ($mensaje !== "") { ?>
        <p>
            <?php echo htmlspecialchars($mensaje, ENT_QUOTES, "UTF-8"); ?>
        </p>
    <?php } ?>

    <form method="POST">

        <label for="nombre">Nombre:</label>

        <input
            type="text"
            id="nombre"
            name="nombre"
            value="<?php echo htmlspecialchars($nombre, ENT_QUOTES, "UTF-8"); ?>"
            required
        >

        <br><br>

        <label for="descripcion">Descripción:</label>

        <textarea
            id="descripcion"
            name="descripcion"
        ><?php echo htmlspecialchars($descripcion ?? "", ENT_QUOTES, "UTF-8"); ?></textarea>

        <br><br>

        <label for="precio">Precio:</label>

        <input
            type="number"
            id="precio"
            name="precio"
            step="0.01"
            min="0"
            value="<?php echo htmlspecialchars($precio, ENT_QUOTES, "UTF-8"); ?>"
            required
        >

        <br><br>

        <label for="stock">Stock:</label>

        <input
            type="number"
            id="stock"
            name="stock"
            min="0"
            value="<?php echo htmlspecialchars($stock, ENT_QUOTES, "UTF-8"); ?>"
            required
        >

        <br><br>

        <button type="submit">Guardar cambios</button>

    </form>

</body>
</html>