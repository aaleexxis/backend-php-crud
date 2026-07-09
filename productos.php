<?php

session_start();

require_once __DIR__ . "/conexion.php";
require_once __DIR__ . "/funciones.php";

// Comprobar que el usuario haya iniciado sesión.
if (!estaLogueado()) {
    header("Location: login.php");
    exit;
}

//Comprobar que solo puede acceder el administrador
if (!esAdministrador()) {
    http_response_code(403);
    exit("No tienes permiso para acceder a esta página.");
}

$busqueda = trim($_GET["busqueda"] ?? "");

if ($busqueda !== "") {
    $consulta = $pdo->prepare(
        "SELECT id, nombre, descripcion, precio, stock, creado_en
         FROM productos
         WHERE nombre LIKE :busqueda
         ORDER BY id DESC"
    );

    $consulta->execute([
        "busqueda" => "%" . $busqueda . "%"
    ]);
} else {
    $consulta = $pdo->query(
        "SELECT id, nombre, descripcion, precio, stock, creado_en
         FROM productos
         ORDER BY id DESC"
    );
}

$productos = $consulta->fetchAll();

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de productos</title>
</head>
<body>

    <h1>Productos</h1>
    
    <form method="GET">

    <label for="busqueda">Buscar producto:</label>

    <input type="text" id="busqueda" name="busqueda" value="<?php echo e($busqueda); ?>" placeholder="Ejemplo: teclado">

    <button type="submit">Buscar</button>

    <a href="productos.php">Limpiar</a>

</form>

<br>

    <p>
        <a href="panel.php">Volver al panel</a>
    </p>

    <p>
        <a href="crear_producto.php">Crear producto</a>
    </p>

    <?php if (count($productos) === 0) { ?>

        <p>No hay productos registrados.</p>

    <?php } else { ?>

        <table border="1" cellpadding="8">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Precio</th>
                    <th>Stock</th>
                    <th>Fecha de creación</th>
                    <th>Acciones</th>
                </tr>
            </thead>

            <tbody>

                <?php foreach ($productos as $producto) { ?>

                    <tr>
                        
                        <td>
                            <?php echo (int) $producto["id"]; ?>
                        </td>

                        <td>
                            <?php
                            echo e($producto["nombre"]);
                            ?>
                        </td>

                        <td>
                            <?php
                            echo e($producto["descripcion"] ?? "");
                            ?>
                        </td>

                        <td>
                            <?php echo number_format((float) $producto["precio"], 2); ?> €
                        </td>

                        <td>
                            <?php echo (int) $producto["stock"]; ?>
                        </td>

                        <td>
                            <?php
                            echo e($producto["creado_en"]);
                            ?>
                        </td>

                        <td>
                            <a href="editar_producto.php?id=<?php echo (int) $producto["id"]; ?>">
                                Editar
                            </a>

                            |

                            <a href="eliminar_producto.php?id=<?php echo (int) $producto["id"]; ?>">
                                Eliminar
                            </a>
                        </td>

                    </tr>

                <?php } ?>

            </tbody>
        </table>

    <?php } ?>

</body>
</html>