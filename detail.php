<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php 
    $noticia_id = isset($_GET["noticia"]) ? $_GET["noticia"] : null;
    if ($noticia_id === null || empty($noticia_id)){
        header("Location: 404.php");
        die();
    }
    require_once("panel/includes/db.php");
    $stmt = $conx->prepare("SELECT * FROM noticias WHERE id = ?");
    $stmt->bind_param("i", $noticia_id);
    $stmt->execute();
    $res = $stmt->get_result();
    $stmt->close();
    if($res->num_rows != 1){
        header("Location: 404.php");
        die();
    }
    $notiObj = $res->fetch_object();

    $fecha = new DateTime($notiObj->fecha);
    $fechaFormateada = $fecha->format('d M Y - H:i');
    $fechaFormateada = strtoupper($fechaFormateada);

    $stmt = $conx->prepare("SELECT * FROM usuarios WHERE id = ?");
    $stmt->bind_param("i", $notiObj->id_usuarios);
    $stmt->execute();
    $res = $stmt->get_result();
    $usuario = $res->fetch_object();
    $publishingUser = $usuario->username;
    $stmt->close();
    ?>
    <link rel="stylesheet" href="includes/css/global.css">
    <link rel="stylesheet" href="includes/css/detail.css">
    <title><?php echo $notiObj->titulo ?></title>
</head>
<body>
    <?php include("includes/nav.php"); ?>
    <div class="detalle-noticia">
        <h1><?php echo $notiObj->titulo ?></h1>
        <summary class="summary"><?php echo $notiObj->descripcion ?></summary>
        <img src="<?php echo $notiObj->imagen?>" alt="Imagen de noticia">
        <h4><?php echo $publishingUser." | ".$fechaFormateada ?></h4>
        <pre><?php echo $notiObj->texto ?></pre>
    </div>
</body>
</html>