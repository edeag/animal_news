<?php

$servidor = "localhost";
$user = "root";
$password = "";
$database = "web_noticias_animales";

$conx = new mysqli($servidor, $user, $password, $database);

if ($conx->connect_error){
    die("Error de conexión: ". $conx->connect_error);
}

?>