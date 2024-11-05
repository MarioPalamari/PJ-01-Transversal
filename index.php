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
            <img src="./img/logo.webp" alt="Logo de la página">
        </div>
        <div>
            <form action="./procLogin/validacionLogin.php" method="POST">
                <input type="text" name="usuario" placeholder="Nombre de usuario...">
                <input type="password" name="password" placeholder="Contraseña...">
                <button type="submit">ENTRAR</button>
                <?php
                if (isset($_GET['error']) && $_GET['error'] == 1) {
                    echo "<p style='color: red'>Usuario o contraseña incorrectos</p>";
                }
                ?>
            </form>
        </div>
    </div>
    <script src="./validaciones/validaciones.js"></script>
</body>
</html>
