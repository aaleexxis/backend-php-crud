<?php

session_start();

require_once __DIR__ . "/conexion.php";
require_once __DIR__ . "/funciones.php";

requerirAdministrador();

$consulta = $pdo->query(
    "SELECT id, usuario, rol, creado_en
     FROM usuarios
     ORDER BY id DESC"
);

$usuarios = $consulta->fetchAll();

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de usuarios</title>
</head>
<body>

    <h1>Usuarios registrados</h1>

    <p>
        <a href="panel.php">Volver al panel</a>
    </p>

    <?php if (count($usuarios) === 0) { ?>

        <p>No hay usuarios registrados.</p>

    <?php } else { ?>

        <table border="1" cellpadding="8">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Usuario</th>
                    <th>Rol</th>
                    <th>Fecha de registro</th>
                    <th>Acciones</th>
                </tr>
            </thead>

            <tbody>

                <?php foreach ($usuarios as $usuario) { ?>

                    <tr>
                        <td>
                            <?php echo (int) $usuario["id"]; ?>
                        </td>

                        <td>
                            <?php echo e($usuario["usuario"]); ?>
                        </td>

                        <td>
                            <?php echo e($usuario["rol"]); ?>
                        </td>

                        <td>
                            <?php echo e($usuario["creado_en"]); ?>
                        </td>

                        <td>
                            <a href="editar_usuario.php?id=<?php echo (int) $usuario["id"]; ?>">
                                Editar
                            </a>

                            |

                            <?php if ((int) $usuario["id"] !== (int) $_SESSION["usuario_id"]) { ?>

                                <a href="eliminar_usuario.php?id=<?php echo (int) $usuario["id"]; ?>">
                                    Eliminar
                                </a>

                            <?php } else { ?>

                                No puedes eliminarte

                            <?php } ?>
                        </td>
                    </tr>

                <?php } ?>

            </tbody>
        </table>

    <?php } ?>

</body>
</html>