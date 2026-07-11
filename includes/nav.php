<nav>
    <a href="panel.php">Panel</a>

    <?php if (esAdministrador()) { ?>
        |
        <a href="usuarios.php">Usuarios</a>
        |
        <a href="productos.php">Productos</a>
    <?php } ?>

    |
    <a href="logout.php">Cerrar sesión</a>
</nav>

<hr>