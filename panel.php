<?php

session_start();

require_once __DIR__ . "/conexion.php";
require_once __DIR__ . "/funciones.php";

requerirLogin();

$usuarioSeguro = e($_SESSION["usuario"]);
$rolSeguro = e($_SESSION["rol"] ?? "Sin rol");

$totalUsuarios = 0;
$totalProductos = 0;
$productosAgotados = 0;
$valorInventario = 0;

if (esAdministrador()) {
    $consultaUsuarios = $pdo->query(
        "SELECT COUNT(*) FROM usuarios"
    );

    $totalUsuarios = (int) $consultaUsuarios->fetchColumn();

    $consultaProductos = $pdo->query(
        "SELECT COUNT(*) FROM productos"
    );

    $totalProductos = (int) $consultaProductos->fetchColumn();

    $consultaAgotados = $pdo->query(
        "SELECT COUNT(*) FROM productos
         WHERE stock = 0"
    );

    $productosAgotados = (int) $consultaAgotados->fetchColumn();

    $consultaValor = $pdo->query(
        "SELECT COALESCE(SUM(precio * stock), 0)
         FROM productos"
    );

    $valorInventario = (float) $consultaValor->fetchColumn();
}

$titulo = "Panel privado";

require_once __DIR__ . "/includes/header.php";
require_once __DIR__ . "/includes/nav.php";

?>

<h1>Panel privado</h1>

<p>
    Bienvenido, <?php echo $usuarioSeguro; ?>.
</p>

<p>
    Tu rol es: <?php echo $rolSeguro; ?>.
</p>

<?php if (esAdministrador()) { ?>

    <h2>Dashboard</h2>

    <table border="1" cellpadding="8">
        <tr>
            <th>Total de usuarios</th>
            <td><?php echo $totalUsuarios; ?></td>
        </tr>

        <tr>
            <th>Total de productos</th>
            <td><?php echo $totalProductos; ?></td>
        </tr>

        <tr>
            <th>Productos agotados</th>
            <td><?php echo $productosAgotados; ?></td>
        </tr>

        <tr>
            <th>Valor total del inventario</th>
            <td><?php echo number_format($valorInventario, 2); ?> €</td>
        </tr>
    </table>

<?php } ?>

<?php require_once __DIR__ . "/includes/footer.php"; ?>