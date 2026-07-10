<?php

session_start();

require_once __DIR__ . "/conexion.php";
require_once __DIR__ . "/funciones.php";

requerirAdministrador();

$error = "";

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

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $csrfToken = $_POST["csrf_token"] ?? "";

    if (!verificarTokenCsrf($csrfToken)) {
        $error = "Solicitud no válida.";
    } else {
        $eliminacion = $pdo->prepare(
            "DELETE FROM productos
             WHERE id = :id"
        );

        $eliminacion->execute([
            "id" => $id
        ]);

        header("Location: productos.php");
        exit;
    }
}

$nombreSeguro = e($producto["nombre"]);
$descripcionSegura = e($producto["descripcion"] ?? "");
$precioSeguro = number_format((float) $producto["precio"], 2);
$stockSeguro = (int) $producto["stock"];

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eliminar producto</title>
</head>
<body>

    <h1>Eliminar producto</h1>

    <p>
        ¿Seguro que quieres eliminar este producto?
    </p>

    <p>
        Producto: <strong><?php echo $nombreSeguro; ?></strong>
    </p>

    <p>
        Descripción: <?php echo $descripcionSegura; ?>
    </p>

    <p>
        Precio: <?php echo $precioSeguro; ?> €
    </p>

    <p>
        Stock: <?php echo $stockSeguro; ?>
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
        <a href="productos.php">Cancelar</a>
    </p>

</body>
</html>