<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../includes/css/dashboard.css">
    <title>Usuarios</title>
</head>
<body>
    <div class="contenedor-dashboard">
        <?php include "menu_dashboard.php"; ?>
        <div class="contenedor-principal">
            <div class="notificaciones">
                <?php
                $status = isset($_GET["status"]) ? $_GET["status"] : null;
                switch ($status) {
                    case "error_delete":
                        echo "<p class='mensaje-error'>Error al borrar el usuario. Intente nuevamente.</p>";
                        break;
                    case "error_mod":
                        echo "<p class='mensaje-error'>Error al modificar el usuario. Intente nuevamente.</p>";
                        break;
                    case "success_delete":
                        echo "<p class='mensaje-exito'>Usuario eliminado con éxito.</p>";
                        break;
                    case "success_mod":
                        echo "<p class='mensaje-exito'>Usuario modificado con éxito.</p>";
                        break;
                    case "success_register":
                        echo "<p class='mensaje-exito'>Usuario creado con éxito.</p>";
                        break;
                }
                require_once "../../includes/db.php";
                $stmt = $conx->prepare("SELECT * FROM usuarios");
                $stmt->execute();
                $res = $stmt->get_result();
                ?>
            </div>

            <div class="tabla-usuarios">
                <h3>Lista de Usuarios</h3>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Contraseña</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($fila = $res->fetch_object()) { ?>
                        <tr>
                            <td ><?php echo $fila->id; ?></td>
                            <td id="username_<?php echo $fila->id?>"><?php echo $fila->username; ?></td>
                            <td id="email_<?php echo $fila->id?>"><?php echo $fila->email; ?></td>
                            <td id="password_<?php echo $fila->id?>"><?php echo $fila->password?></td>
                            <td id="actions_<?php echo $fila->id?>">
                                <a href="#" onclick="toggleEdit('<?php echo $fila->id; ?>', '<?php echo $fila->username?>',
                                                                '<?php echo $fila->email?>', '<?php echo $fila->password?>')">Editar</a>
                                <a href="#" onclick="popup('../../controllers/UserController.php?op=DELETE&delete_id=<?php echo $fila->id; ?>')">Eliminar</a>
                            </td>
                        </tr>
                        <?php } $stmt->close(); ?>
                    </tbody>
                </table>
            </div>

            <div class="crear-usuario">
                <h3>Crear Nuevo Usuario</h3>
                <form action="../../controllers/UserController.php?op=REGISTER" method="post">
                    <label>Nombre de Usuario:</label>
                    <input type="text" name="username" required><br>
                    <label>Email:</label>
                    <input type="email" name="email" required><br>
                    <label>Contraseña:</label>
                    <input type="password" name="password" required><br>
                    <input type="submit" name="send" value="Crear">
                </form>
            </div>
        </div>
    </div>
    <script src="../../includes/js/functions.js" defer></script>
</body>
</html>