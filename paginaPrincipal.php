<!-- Eleccion de los diferentes salones -->
<?php session_start(); 
include('./conexion/conexion.php');

if (!isset($_SESSION['usuario'])) {
    echo "<h6>Por favor, inicie sesión.</h6>";
    exit;
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selección de Salas</title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>
<body>
    <div class="container">
        <h1>SALAS</h1>
        <div class="room-sections">
            <!-- Sección de Terrazas -->
            <div class="room-category">
                <img src="./img/terraza.webp" class="pinchable" data-nombre="Terrazas" alt="Terrazas">
                <div class="buttons">
                    <form action="./salones/terraza1.php">
                    <button><img class="nums" src="./img/nums/1.webp" alt=""></button>
                    </form>
                    <form action="./salones/terraza2.php">
                    <button><img class="nums" src="./img/nums/2.webp" alt=""></button>
                    </form>
                    <form action="./salones/terraza3.php">
                    <button><img class="nums" src="./img/nums/3.webp" alt=""></button>
                    </form>
                </div>
            </div>

            <!-- Sección de Salones Principales -->
            <div class="room-category">
                <img class="salon" src="./img/salon.webp" alt="Salones Principales">
                <div class="buttons">
                    <form action="./salones/salon1.php">
                    <button><img class="nums" src="./img/nums/1.webp" alt=""></button>
                    </form>
                    <form action="./salones/salon2.php">
                    <button><img class="nums" src="./img/nums/2.webp" alt=""></button>
                    </form>
                </div>
            </div>

            <!-- Sección de Salas Privadas -->
            <div class="room-category">
                <img src="./img/vip.webp" alt="Salas Privadas">
                <div class="buttons">
                    <form action="./salones/vip1.php">
                    <button><img class="nums" src="./img/nums/1.webp"></button>
                    </form>
                    <form action="./salones/vip2.php">
                    <button><img class="nums" src="./img/nums/2.webp" alt=""></button>
                    </form>
                    <form action="./salones/vip3.php">
                    <button><img class="nums" src="./img/nums/3.webp" alt=""></button>
                    </form>
                    <form action="./salones/vip4.php">
                    <button><img class="nums" src="./img/nums/4.webp" alt=""></button>
                    </form>
                </div>
            </div>
        </div>
        <button class="logout-button" onclick="logout()">Cerrar Sesión</button>
    </div>


<script src="./validaciones/funciones.js"></script>
</body>
</html>

