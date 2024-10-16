<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" href="../../includes/css/login.css">
    <script src="../../includes/js/functions.js" defer></script>
</head>
<body>
    <div class="contenedor-principal">
        <div class="centrar-login">
            <div class="container">
                <h1>Iniciar Sesión</h1>
                <?php 
                $error = isset($_GET["error"]) ? $_GET["error"] : null;
                if ($error == 1) {
                    echo "<div class='mensaje-error'>Usuario o Contraseña inválida.</div>";
                } 
                ?>
                <form action="../../controllers/UserController.php?op=LOGIN" method="post">
                    <input type="text" name="username" placeholder="Usuario" required>
                    <div class="password-container">
                        <input type="password" name="password" class="password" placeholder="Contraseña" required>
                        <button type="button" name="toggleButton" class="toggleButton" onclick="togglePassword()">Mostrar</button>
                    </div>
                    <input type="submit" value="Iniciar Sesión">
                </form>
            </div>
        </div>
    </div>
</body>
</html>