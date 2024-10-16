<!DOCTYPE html>
<html lang="es" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="includes/css/global.css">
    <link rel="stylesheet" href="includes/css/home.css">
    <title>Inicio</title>
</head>
<body>
    <?php include("includes/nav.php"); ?>
    <?php
    require_once("panel/includes/db.php");
    $stmt = $conx->prepare("SELECT * FROM noticias");
    $stmt->execute();
    $res = $stmt->get_result();
    $stmt->close();
    ?>
    <div class="contenedor">
        <div class="grid">
            <?php
            while($fila = $res->fetch_object()){
                $stmt = $conx->prepare("SELECT * FROM categorias WHERE id = ?");
                $stmt->bind_param("i", $fila->id_categoria);
                $stmt->execute();
                $res2 = $stmt->get_result();
                $categoriaObj = $res2->fetch_object();
                $stmt->close();
                echo 
                '<article class="tarjeta-noticia" id="noticia_'.$fila->id.'">
                <a href="detail.php?noticia='.$fila->id.'">
                        <h4>'.$categoriaObj->nombre_categoria.'</h4>
                        <img src="'.$fila->imagen.'" alt="Imagen de noticia">
                        <h2>'.$fila->titulo.'</h2>
                        <summary>'.$fila->descripcion.'</summary>
                    </a>
                </article>';
            }
            ?>
        </div>
    </div>
</body>
</html>