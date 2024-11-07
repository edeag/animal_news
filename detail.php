<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="includes/css/global.css">
    <?php 
    $noticia_id = isset($_GET["noticia"]) ? $_GET["noticia"] : null;
    if ($noticia_id === null || empty($noticia_id)){
        header("Location: 404.php");
        die();
    }
    require_once("panel/includes/db.php");
    $stmt = $conx->prepare("SELECT * FROM noticias n JOIN categorias c ON
                            n.id_categoria = c.id WHERE n.id = ?");
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
    <section class="detalle-noticia wrapper">
        <h3><?php echo $notiObj->nombre_categoria?></h3>
        <h1><?php echo $notiObj->titulo ?></h1>
        <summary><?php echo $notiObj->descripcion ?></summary>
        <img src="<?php echo $notiObj->imagen?>" alt="Imagen de noticia">
        <h3><?php echo $publishingUser." | ".$fechaFormateada ?></h3>
        <p><?php echo $notiObj->texto ?></p>
        <div class="divider"></div>
    </section>
    <h2 class="noticias-relacionadas-h2 wrapper">Noticias Relacionadas</h2>
    <section class="noticias-relacionadas noticias wrapper">
        <?php
        $stmt = $conx->prepare("SELECT n.*, c.nombre_categoria FROM noticias n
                                            JOIN categorias c ON n.id_categoria = c.id
                                            WHERE c.id = ? AND n.id != ?
                                            ORDER BY n.fecha DESC
                                            LIMIT 3");
        $idCategoria = $notiObj->id_categoria;
        $stmt->bind_param("ii", $idCategoria, $noticia_id);
        $stmt->execute();
        $relacionadoRes = $stmt->get_result();
        $stmt->close();

        while($relacionadoObj = $relacionadoRes->fetch_object()){
        ?>
        <section class="noticia-item">
            <a href="detail.php?noticia=<?php echo $relacionadoObj->id ?>">
                <h3 class="categoria-noticias"><?php echo $relacionadoObj->nombre_categoria ?></h3>
                <img class="imagen-noticias" src="<?php echo $relacionadoObj->imagen ?>">
                <h1 class="titulo-noticias"><?php echo $relacionadoObj->titulo ?></h1>
                <p class="desc-noticias"><?php echo $relacionadoObj->descripcion ?></p>
            </a>
        </section>
        <?php } ?>
    </section>
    <?php include("includes/footer.php") ?>
</body>
</html>