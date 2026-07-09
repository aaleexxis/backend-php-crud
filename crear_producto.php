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

$nombre = "";
$descripcion = "";
$precio = "";
$stock = "";

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

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear producto</title>
</head>
<body>

    <h1>Crear producto</h1>

    <p>
        <a href="productos.php">Volver a productos</a>
    </p>

    <?php if ($error !== "") { ?>
        <p>
            <?php echo htmlspecialchars($error, ENT_QUOTES, "UTF-8"); ?>
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
        ><?php echo htmlspecialchars($descripcion, ENT_QUOTES, "UTF-8"); ?></textarea>

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

        <button type="submit">Guardar producto</button>

    </form>

</body>
</html>