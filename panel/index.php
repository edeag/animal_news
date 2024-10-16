<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>redirect</title>
</head>
<body>
    <?php 
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
    if (!isset($_SESSION["username"]) || empty($_SESSION["username"])) {
        header("Location: views/auth/login.php");
        die();
    } else {
        header("Location: views/dashboard/usuarios.php");
    }
    ?>
</body>
</html>