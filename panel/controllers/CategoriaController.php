<?php 

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
if (!isset($_SESSION["username"]) || empty($_SESSION["username"])) {
    header("Location: ../views/auth/login.php");
    die();
}

require_once("../includes/db.php");

$operation = isset($_GET["op"]) ? $_GET["op"] : null;
if ($operation === null || empty($operation)){
    header("Location: ../views/dashboard/categorias.php?status=error_generic");
    die();
}

if($operation == "CREATE"){
    $nombreCategoria = $_POST["nom_cate"];
    $stmt = $conx->prepare("INSERT INTO categorias (nombre_categoria) VALUES (?)");
    $stmt->bind_param("s", $nombreCategoria);
    if ($stmt->execute()){
        $status = "success_create";
    } else {
        $status = "error_create";
    }
    $stmt->close();
    header("Location: ../views/dashboard/categorias.php?status=".$status);
    die();
} elseif($operation == "DELETE"){
    $categoria_id = isset($_POST["noticia_id"]) ? $_POST["noticia_id"] : null ;
    $stmt = $conx->prepare("DELETE FROM categorias WHERE id = ?");
    $stmt->bind_param("i", $categoria_id);
    if($stmt->execute()){
        $status = "success_delete";
    } else {
        $status = "error_delete";
    }
    $stmt->close();
    header("Location: ../views/dashboard/categorias.php?status=".$status);
    die();
}
?>