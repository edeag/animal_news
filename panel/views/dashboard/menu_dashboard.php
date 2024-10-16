<?php
    if (session_status() !== PHP_SESSION_ACTIVE){
        session_start();
    }
    if (!isset($_SESSION["username"]) || empty("username")){
        header("Location: ../auth/login.php");
        die();
    }
?>

<div class="sidebar">
    <ul class="menu-lista">
        <li class="menu-item"><a href="entradas.php">Entradas</a></li>
        <li class="menu-item"><a href="categorias.php">Categorías</a></li>
        <li class="menu-item"><a href="usuarios.php">Usuarios</a></li>
        <li class="menu-item"><a href="../../controllers/UserController.php?op=LOGOUT">Cerrar Sesión</a></li>
    </ul>
</div>