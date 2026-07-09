<?php

session_start();

require_once __DIR__ . "/conexion.php";

// Comprobar que el usuario haya iniciado sesión.
if (!isset($_SESSION["usuario_id"])) {
    header("Location: login.php");
    exit;
}

// Solo administradores pueden gestionar productos.
if (($_SESSION["rol"] ?? "") !== "Administrador") {
    http_response_code(403);
    exit("No tienes permiso para acceder a esta página.");
}

// Obtener productos de la base de datos.
$consulta = $pdo->query(
    "SELECT id, nombre, descripcion, precio, stock, creado_en
     FROM productos
     ORDER BY id DESC"
);

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
                            echo htmlspecialchars($producto["nombre"],ENT_QUOTES,"UTF-8");
                            ?>
                        </td>

                        <td>
                            <?php
                            echo htmlspecialchars($producto["descripcion"] ?? "",ENT_QUOTES,"UTF-8");
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
                            echo htmlspecialchars($producto["creado_en"],ENT_QUOTES,"UTF-8");
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