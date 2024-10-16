<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../includes/css/dashboard.css">
    <title>Entradas</title>
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
                        echo "<p class='mensaje-exito'>Entrada subida con éxito.</p>";
                        break;
                    case "success_delete":
                        echo "<p class='mensaje-exito'>Entrada eliminada con éxito.</p>";
                        break;
                    case "error_imgUpload":
                        echo "<p class='mensaje-error'>Error al subir la imagen.</p>";
                        break;
                    case "error_create":
                        echo "<p class='mensaje-error'>Error al subir entrada. Intente nuevamente.</p>";
                        break;
                    case "error_delete":
                        echo "<p class='mensaje-error'>Error al eliminar la entrada. Intente nuevamente.</p>";
                        break;
                    case "error_generic":
                        echo "<p class='mensaje-error'>Error. Intente nuevamente.</p>";
                        break;
                }
                require_once "../../includes/db.php";
                $stmt = $conx->prepare("SELECT * FROM categorias");
                $stmt->execute();
                $res = $stmt->get_result();
                ?>
            </div>

            <div class="crear-entrada">
                <h3>Crear Entrada</h3>
                <form enctype="multipart/form-data" action="../../controllers/EntradaController.php?op=CREATE" method="post">
                    <input type="text" name="titulo" placeholder="Título" required>
                    <input type="text" name="desc" placeholder="Descripción" required>
                    <textarea name="contenido" placeholder="Contenido Noticia" required></textarea>
                    <input type="file" name="imagen" accept="image/*">
                    <select name="cate_id" required>
                        <?php 
                        while($fila = $res->fetch_object()){
                            echo '<option value="'.$fila->id.'">'.$fila->nombre_categoria.'</option>';
                        }
                        ?>
                    </select>
                    <input type="submit" name="send" value="Crear">
                </form>
            </div>

            <div class="eliminar-entrada">
                <h3>Eliminar Entrada</h3>
                <form method="post" action="../../controllers/EntradaController.php?op=DELETE">
                    <select name="noticia_id" required>
                        <?php
                        $stmt = $conx->prepare("SELECT * FROM noticias");
                        $stmt->execute();
                        $res = $stmt->get_result();
                        while ($fila = $res->fetch_object()) {
                            echo '<option value="'.$fila->id.'">'.$fila->titulo.'</option>';
                        }
                        $stmt->close();
                        ?>
                    </select>
                    <input type="submit" name="delete" value="Eliminar">
                </form>
            </div>
        </div>
    </div>
</body>
</html>