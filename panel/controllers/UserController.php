<?php 
if(session_status() !== PHP_SESSION_ACTIVE){
    session_start();
}

require_once("../includes/db.php");

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$operation = isset($_GET["op"]) ? $_GET["op"] : null;
$username = isset($_POST["username"]) ? $_POST["username"] : null;
$password = isset($_POST["password"]) ? $_POST["password"] : null;
$email = isset($_POST["email"]) ? $_POST["email"]: null;

if($operation == "LOGIN"){
    $stmt = $conx->prepare("SELECT * FROM usuarios WHERE username = ? AND BINARY password = ?");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $res = $stmt->get_result();
    $stmt->close();

    if($res->num_rows === 1){
        $user = $res->fetch_object();
    } else {
        header("Location: ../index.php?error=1");
        //HACER: modificar el error por status
        die();
    } 
} elseif($operation == "LOGOUT"){
    session_destroy();
    header("Location: /proyecto_web/index.php");
    die();
} elseif($operation == "DELETE"){
    if (!isset($_SESSION["username"]) || empty("username")){
        header("Location: ../auth/login.php");
        die();
    }
    $delete_id = isset($_GET["delete_id"]) ? $_GET["delete_id"] : null;
    if ($delete_id === null) {
        header("Location: ../views/dashboard/usuarios.php?status=error_delete");
        die();
    }

    $stmt = $conx->prepare("SELECT id FROM usuarios WHERE id = ?");
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res->num_rows !== 1) {
        $stmt->close();
        header("Location: ../views/dashboard/usuarios.php?status=error_delete");
        die();
    }

    $stmt = $conx->prepare("UPDATE noticias SET id_usuarios = NULL WHERE id_usuarios = ?");
    //IMPORTANTE! set NULL como default en id_usuarios (noticias)
    $stmt->bind_param("i", $delete_id);
    if(!$stmt->execute()){
        $stmt->close();
        header("Location: ../views/dashboard/usuarios.php?status=error_delete");
        die();
    };


    $stmt = $conx->prepare("DELETE FROM usuarios WHERE id = ?");
    $stmt->bind_param("i", $delete_id);
    $status = ($stmt->execute()) ? "success_delete" : "error_delete";
    $stmt->close();

    if($delete_id == $_SESSION["user_id"]){
        header("Location: UserController.php?op=LOGOUT");
        die();
    }

    header("Location: ../views/dashboard/usuarios.php?status=".$status);
    die();
} elseif($operation == "EDIT"){
    if (!isset($_SESSION["username"]) || empty("username")){
        header("Location: ../views/auth/login.php");
        die();
    }
    $formId = isset($_POST["formId"]) ? $_POST["formId"] : null;
    $newUsername = isset($_POST["newUsername"]) ? $_POST["newUsername"] : null;
    $newEmail = isset($_POST["newEmail"]) ? $_POST["newEmail"] : null;
    $newPassword = isset($_POST["newPassword"]) ? $_POST["newPassword"] : null;

    if($formId === null || $newUsername === null || $newEmail === null || $newPassword === null){
        header("Location: ../views/dashboard/usuarios.php?status=error_edit");
        die();
    }

    $stmt = $conx->prepare("UPDATE usuarios SET username = ?, email = ?, password = ? WHERE id = ?");
    $stmt->bind_param("sssi", $newUsername, $newEmail, $newPassword, $formId);
    $status = $stmt->execute() ? "success_edit" : "error_edit";
    $stmt->close();
    header("Location: ../views/dashboard/usuarios.php?status=".$status);
    die();
} elseif ($operation == "REGISTER"){
    if($username === null || $password === null || $email === null){
        header("Location: ../views/dashboard/usuarios.php?status=error_register");
        die();
    }

    $stmt = $conx->prepare("INSERT INTO usuarios (username, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $email, $password);
    $status = $stmt->execute()? "success_register": "error_register";
    $stmt->close();
    header("Location: ../views/dashboard/usuarios.php?status=".$status);
    die();
}

$_SESSION["user_id"] = $user->id;
$_SESSION["username"] = $user->username;

$_SESSION["email"] = $user->email;
header("Location: ../views/dashboard/usuarios.php");
die();
?>