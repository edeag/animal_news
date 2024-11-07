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
    <main>
        <?php
            include("includes/nav.php");
            require_once("panel/includes/db.php");

            $nPagina = isset($_GET["pagina"]) ? $_GET["pagina"] : 1;
            $limit = 6;
            $offset = (($nPagina - 1) * $limit) + 1;

            $stmt = $conx->prepare("SELECT COUNT(*) as total FROM noticias");
            $stmt->execute();
            $nTotalesRes = $stmt->get_result();
            $stmt->close();
            $nTotales = $nTotalesRes->fetch_object()->total;
            $nTotales = $nTotales - 1;
            $totPaginas = ceil($nTotales / $limit);
        ?>
        <article class="wrapper grid">
        <?php
            if($nPagina == 1){
                $stmt = $conx->prepare("SELECT n.*, c.nombre_categoria FROM noticias n
                                        JOIN categorias c ON n.id_categoria = c.id
                                        ORDER BY n.fecha DESC LIMIT 1");
                $stmt->execute();
                $principalRes = $stmt->get_result();
                $stmt->close();
                $principal = $principalRes->fetch_object();
        ?>

            <section class="principal">
                <a class="noticia-primaria" href="detail.php?noticia=<?php echo $principal->id ?>">
                    <h3 class="<a categoria-principal"><?php echo $principal->nombre_categoria ?></h3>
                    <img class="imagen-principal" src="<?php echo $principal->imagen ?>">
                    <h1 class="titulo-principal"><?php echo $principal->titulo ?></h1>
                    <p class="desc-principal"><?php echo $principal->descripcion ?></p>
                    <button class="boton-principal">Leer más</button>
                </a>
            </section>
        <?php } ?>

            <section class="noticias">
                <?php
                    $stmt = $conx->prepare("SELECT n.*, c.nombre_categoria FROM noticias n
                                            JOIN categorias c ON n.id_categoria = c.id
                                            ORDER BY n.fecha DESC LIMIT ? OFFSET ?");
                    $stmt->bind_param("ii", $limit, $offset);
                    $stmt->execute();
                    $noticias = $stmt->get_result();
                    $stmt->close();

                    while ($filaObj = $noticias->fetch_object()) {
                ?>
                <section class="noticia-item">
                    <a href="detail.php?noticia=<?php echo $filaObj->id ?>">
                        <h3 class="categoria-noticias"><?php echo $filaObj->nombre_categoria ?></h3>
                        <img class="imagen-noticias" src="<?php echo $filaObj->imagen ?>">
                        <h1 class="titulo-noticias"><?php echo $filaObj->titulo ?></h1>
                        <p class="desc-noticias"><?php echo $filaObj->descripcion ?></p>
                    </a>
                </section>
                <?php 
                    }
                ?>
            </section>
        </article>
        <nav class="paginacion">
        <?php
            if($nPagina > 1){
                echo '<a class="paginacionButton" href="?pagina='.($nPagina-1).'">Anterior</a>';
            }
            for($i = 1; $i<=$totPaginas ;$i++){
                if($i == $nPagina){
                    echo '<a class="current paginacionButton"';
                } else {
                    echo '<a class="paginacionButton"';
                }
                echo ' href="?pagina='.($i).'">'.$i.'</a>';
            }
            if($nPagina < $totPaginas){
                echo '<a class="paginacionButton" href="?pagina='.($nPagina+1).'">Siguiente</a>';
            }
        ?>
        </nav>
    </main>
    <?php include("includes/footer.php") ?>
</body>
</html>