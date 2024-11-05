<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="./css/styles.css"> 
</head>
<body>
    <div>
        <div>
            <img src="./img/logo.webp" alt="">  <!-- logo de la pagina -->
        </div>
        <div>
            <form action="./procLogin/validacionLogin.php" method="POST">
                <input type="text" name="usuario" placeholder="Nombre de usuario..." onblur="validaNombre()">
                <input type="password" name="password" placeholder="Contraseña..." onblur="validaContraseña()">
                <button>ENTRAR</button>
                <?php
                    if (isset($_GET['error'])) {
                        $error = $_GET['error'];
                        echo "<p style='color: red'>Usuario o contraseña incorrectos </p>";
                    }
                ?>
            </form>
        </div>
    </div>


<script src="./validaciones/validaciones.js"></script>
</body>
</html>
