<?php

session_start();

require_once __DIR__ . "/conexion.php";
require_once __DIR__ . "/funciones.php";

// Comprobar que el usuario haya iniciado sesión.
if (!estaLogueado()) {
    header("Location: login.php");
    exit;
}

// Comprobar que solo puede acceder el administrador.
if (!esAdministrador()) {
    http_response_code(403);
    exit("No tienes permiso para acceder a esta página.");
}

$busqueda = trim($_GET["busqueda"] ?? "");
$filtroStock = $_GET["stock"] ?? "";
$orden = $_GET["orden"] ?? "recientes";
$porPagina = 5;

$pagina = filter_input(INPUT_GET, "pagina", FILTER_VALIDATE_INT);

if ($pagina === false || $pagina === null || $pagina < 1) {
    $pagina = 1;
}

$offset = ($pagina - 1) * $porPagina;

$condiciones = [];
$parametros = [];

if ($busqueda !== "") {
    $condiciones[] = "nombre LIKE :busqueda";
    $parametros["busqueda"] = "%" . $busqueda . "%";
}

if ($filtroStock === "disponibles") {
    $condiciones[] = "stock > 0";
} elseif ($filtroStock === "agotados") {
    $condiciones[] = "stock = 0";
}
$sqlConteo = "SELECT COUNT(*) FROM productos";

if (count($condiciones) > 0) {
    $sqlConteo .= " WHERE " . implode(" AND ", $condiciones);
}

$consultaConteo = $pdo->prepare($sqlConteo);
$consultaConteo->execute($parametros);

$totalProductos = (int) $consultaConteo->fetchColumn();

$totalPaginas = (int) ceil($totalProductos / $porPagina);

if ($totalPaginas < 1) {
    $totalPaginas = 1;
}

if ($pagina > $totalPaginas) {
    $pagina = $totalPaginas;
    $offset = ($pagina - 1) * $porPagina;
}

$sql = "SELECT id, nombre, descripcion, precio, stock, creado_en
        FROM productos";

if (count($condiciones) > 0) {
    $sql .= " WHERE " . implode(" AND ", $condiciones);
}

switch ($orden) {
    case "nombre_asc":
        $sql .= " ORDER BY nombre ASC";
        break;

    case "precio_asc":
        $sql .= " ORDER BY precio ASC";
        break;

    case "precio_desc":
        $sql .= " ORDER BY precio DESC";
        break;

    case "stock_desc":
        $sql .= " ORDER BY stock DESC";
        break;

    default:
        $sql .= " ORDER BY id DESC";
        break;
}

$sql .= " LIMIT $porPagina OFFSET $offset";

$consulta = $pdo->prepare($sql);
$consulta->execute($parametros);

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

        <input
            type="text"
            id="busqueda"
            name="busqueda"
            value="<?php echo e($busqueda); ?>"
            placeholder="Ejemplo: teclado"
        >

        <label for="stock">Stock:</label>

        <select id="stock" name="stock">
            <option value="">Todos</option>

            <option
                value="disponibles"
                <?php if ($filtroStock === "disponibles") { echo "selected"; } ?>
            >
                Disponibles
            </option>

            <option
                value="agotados"
                <?php if ($filtroStock === "agotados") { echo "selected"; } ?>
            >
                Agotados
            </option>
        </select>

        <label for="orden">Ordenar por:</label>

<select id="orden" name="orden">
    <option
        value="recientes"
        <?php if ($orden === "recientes") { echo "selected"; } ?>
    >
        Más recientes
    </option>

    <option
        value="nombre_asc"
        <?php if ($orden === "nombre_asc") { echo "selected"; } ?>
    >
        Nombre A-Z
    </option>

    <option
        value="precio_asc"
        <?php if ($orden === "precio_asc") { echo "selected"; } ?>
    >
        Precio menor a mayor
    </option>

    <option
        value="precio_desc"
        <?php if ($orden === "precio_desc") { echo "selected"; } ?>
    >
        Precio mayor a menor
    </option>

    <option
        value="stock_desc"
        <?php if ($orden === "stock_desc") { echo "selected"; } ?>
    >
        Más stock
    </option>
</select>

        <button type="submit">Filtrar</button>

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
                            <?php echo e($producto["nombre"]); ?>
                        </td>

                        <td>
                            <?php echo e($producto["descripcion"] ?? ""); ?>
                        </td>

                        <td>
                            <?php echo number_format((float) $producto["precio"], 2); ?> €
                        </td>

                        <td>
                            <?php echo (int) $producto["stock"]; ?>
                        </td>

                        <td>
                            <?php echo e($producto["creado_en"]); ?>
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

    <?php if ($totalPaginas > 1) { ?>

        <p>
            Páginas:

            <?php for ($i = 1; $i <= $totalPaginas; $i++) { ?>

                <?php
                $url = "productos.php?" . http_build_query([
                    "busqueda" => $busqueda,
                    "stock" => $filtroStock,
                    "orden" => $orden,
                    "pagina" => $i
                ]);
                ?>

                <?php if ($i === $pagina) { ?>
                    <strong><?php echo $i; ?></strong>
                <?php } else { ?>
                    <a href="<?php echo e($url); ?>">
                        <?php echo $i; ?>
                    </a>
                <?php } ?>

            <?php } ?>
        </p>

    <?php } ?>

    <?php } ?>

</body>
</html>