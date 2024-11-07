<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="includes/css/global.css">
    <title>Busqueda</title>
</head>
<body>
    
    <?php
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);        
        
        require_once("panel/includes/db.php");

        $searchInput = isset($_GET["searchInput"]) ? $_GET["searchInput"] : null;
        $searchInputParam = '%'.$searchInput.'%';
        ?>

    <form class="ordenForm wrapper" action="" method="get">
        <div>
            <label>Orden:</label>
            <select name="orden">
                <option value="DESC">Nuevos</option>
                <option value="ASC">Antiguos</option>
            </select>
        </div>
        <div>
            <label>Categoria: </label>
            <select name="categoria">
                <option value="any">cualquiera</option>
                <?php
                    $stmt = $conx->prepare("SELECT * FROM categorias");
                    $stmt->execute();
                    $categoriasRes = $stmt->get_result();
                    $stmt->close();

                    while ($categoria = $categoriasRes->fetch_object()){
                        echo '<option value="'.$categoria->id.'">'.$categoria->nombre_categoria.'</option>';
                    }
                ?>
            </select>
        </div>
        <input type="hidden" name="searchInput" value="<?php echo $searchInput?>">
        <button type="submit">Filtrar</button>
    </form>

    <?php
        $orden = isset($_GET["orden"]) ? $_GET["orden"] : "DESC";
        if ($orden !== "ASC" && $orden !== "DESC") {
            $orden = "DESC";
        }

        $idCategoria = isset($_GET["categoria"]) ? $_GET["categoria"] : "any";
        if ($idCategoria != "any"){
            $filtroCategoria = "AND c.id = $idCategoria";
        } else {
            $filtroCategoria = "";
        }
        

        $nPagina = isset($_GET["pagina"]) ? $_GET["pagina"] : 1;
        $limit = 6;
        $offset = ($nPagina - 1) * $limit;

        include("includes/nav.php");

        $stmt = $conx->prepare("SELECT COUNT(*) as total FROM noticias n
                                JOIN categorias c ON n.id_categoria = c.id
                                WHERE n.titulo LIKE ? $filtroCategoria");
        $stmt->bind_param("s", $searchInputParam);
        $stmt->execute();
        $nTotalesRes = $stmt->get_result();
        $nTotales = $nTotalesRes->fetch_object()->total;
        $stmt->close();

        $totPaginas = ceil($nTotales / $limit);

        $parametrosGET = $_GET;
        unset($parametrosGET['pagina']);

        ?>
    <section class="noticias wrapper">
        <?php
        $stmt = $conx->prepare("SELECT n.*, c.nombre_categoria FROM noticias n
                                            JOIN categorias c ON n.id_categoria = c.id
                                            WHERE n.titulo LIKE ? $filtroCategoria
                                            ORDER BY n.fecha $orden
                                            LIMIT ? OFFSET ?");
        $stmt->bind_param("sii", $searchInputParam, $limit, $offset);
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
        <?php } ?>
    </section>
        
    <nav class="paginacion">
        <?php
            if($nPagina > 1){
                echo '<a class="paginacionButton" href="?'.http_build_query(array_merge($parametrosGET,['pagina'=>$nPagina-1])).'">Anterior</a>';}
            for($i = 1; $i<=$totPaginas ;$i++){
                if($i == $nPagina){
                    echo '<a class="current paginacionButton"';
                } else {
                    echo '<a class="paginacionButton"';
                }
                echo ' href="?'.http_build_query(array_merge($parametrosGET,['pagina'=>$i])).'">'.$i.'</a>';
            }
            if($nPagina < $totPaginas){
                echo '<a class="paginacionButton" href="?'.http_build_query(array_merge($parametrosGET,['pagina'=>$nPagina+1])).'">Siguiente</a>';}
        ?>
    </nav>
    <?php include("includes/footer.php") ?>
</body>
</html>