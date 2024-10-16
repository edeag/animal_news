<?php 
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
if (!isset($_SESSION["username"]) || empty($_SESSION["username"])) {
    header("Location: ../views/auth/login.php");
    die();
}

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$operation = isset($_GET["op"]) ? $_GET["op"] : null ;
if($operation == null){
    header("Location: ../views/dashboard/entradas.php?status=error_generic");
    die();
}

require_once("../includes/db.php");
if($operation == "CREATE"){

    $uploadFolder = "includes/img/".$_FILES["imagen"]["name"];
    if(!move_uploaded_file($_FILES["imagen"]["tmp_name"], "../../".$uploadFolder)){
        header("Location: ../views/dashboard/entradas.php?status=error_imgUpload");
        die();
    }

    $titulo = $_POST["titulo"];
    $desc = $_POST["desc"];
    $texto = $_POST["contenido"];
    $fecha = date("Y-m-d H:i:s");
    $id_categoria = $_POST["cate_id"];
    $id_usuario = $_SESSION["user_id"];

    $stmt = $conx->prepare("INSERT INTO noticias (titulo, descripcion, texto, imagen, fecha, id_categoria, id_usuarios) VALUES (?,?,?,?,?,?,?)");
    $stmt->bind_param("sssssii", $titulo, $desc, $texto, $uploadFolder, $fecha, $id_categoria, $id_usuario);
    if($stmt->execute()){
        $status = "success_create";
    } else{
        $status = "error_create";
    }
    $stmt->close();
    header("Location: ../views/dashboard/entradas.php?status=".$status);
    die();

} elseif($operation == "DELETE"){
    $noti_id = isset($_POST["noticia_id"]) ? $_POST["noticia_id"] : null;
    if($noti_id === null){
        header("Location: ../views/dashboard/entradas.php?status=error_delete");
        die();
    }

    $stmt = $conx->prepare("DELETE FROM noticias WHERE id = ?");
    $stmt->bind_param("i", $noti_id);
    if($stmt->execute()){
        $status = "success_delete";
    } else {
        $status = "error_delete";
    }
    $stmt->close();
    header("Location: ../views/dashboard/entradas.php?status=".$status);
    die();
}
?>