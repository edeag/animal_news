<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../includes/css/dashboard.css">
    <title>Categorías</title>
</head>
<body>
    <div class="contenedor-dashboard">
        <?php include "menu_dashboard.php"; ?>
        <div class="contenedor-principal">
            <div class="notificaciones">
                <?php
                $status = isset($_GET["status"]) ? $_GET["status"] : null;
                switch ($status) {
                    case "success_create":
                        echo "<p class='mensaje-exito'>Categoría creada con éxito.</p>";
                        break;
                    case "success_delete":
                        echo "<p class='mensaje-exito'>Categoría eliminada con éxito.</p>";
                        break;
                    case "error_create":
                        echo "<p class='mensaje-error'>Error al crear categoría. Intente nuevamente.</p>";
                        break;
                    case "error_delete":
                        echo "<p class='mensaje-error'>Error al eliminar categoría. Intente nuevamente.</p>";
                        break;
                    case "error_generic":
                        echo "<p class='mensaje-error'>Error. Intente nuevamente.</p>";
                        break;
                }
                require_once("../../includes/db.php");
                $stmt = $conx->prepare("SELECT * FROM categorias");
                $stmt->execute();
                $res = $stmt->get_result();
                $stmt->close();
                ?>
            </div>

            <div class="crear-categoria">
                <h3>Crear Categoría</h3>
                <form action="../../controllers/CategoriaController.php?op=CREATE" method="post">
                    <input type="text" name="nom_cate" placeholder="Nombre Categoría" required>
                    <input type="submit" name="send" value="Crear">
                </form>
            </div>

            <div class="eliminar-categoria">
                <h3>Eliminar Categoría</h3>
                <form action="../../controllers/CategoriaController.php?op=DELETE" method="post">
                    <select name="noticia_id" required>
                        <?php 
                        while($fila = $res->fetch_object()){
                            echo '<option value="'.$fila->id.'">'.$fila->nombre_categoria.'</option>';
                        }
                        ?>
                    </select>
                    <input type="submit" name="eliminar" value="Eliminar">
                </form>
            </div>
        </div>
    </div>
</body>
</html>