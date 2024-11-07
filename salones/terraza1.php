<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terraza I</title>
    <link rel="stylesheet" href="../styles.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="container2">
        <div class="header">
            <img src="../img/terraza1.png" alt="" class="imgTerraza1">
        </div>
        <div class="grid">
            <div class="table" id="mesa1" onclick="handleClick(1)">
                <img id="imgMesa1" src="../img/sombrilla.webp" alt="Mesa 1">
                <p>mesa I</p>
            </div>
            <div class="table" id="mesa2" onclick="handleClick(2)">
                <img id="imgMesa2" src="../img/sombrilla.webp" alt="Mesa 2">
                <p>mesa II</p>
            </div>
            <div class="table" id="mesa3" onclick="handleClick(3)">
                <img id="imgMesa3" src="../img/sombrilla.webp" alt="Mesa 3">
                <p>mesa III</p>
            </div>
            <div class="table" id="mesa4" onclick="handleClick(4)">
                <img id="imgMesa4" src="../img/sombrilla.webp" alt="Mesa 4">
                <p>mesa IV</p>
            </div>
            <div class="table" id="mesa5" onclick="handleClick(5)">
                <img id="imgMesa5" src="../img/sombrilla.webp" alt="Mesa 5">
                <p>mesa V</p>
            </div>
        </div>

        <button class="logout-button" onclick="logout1()">Cerrar Sesión</button>
        <form action="../paginaPrincipal.php">
            <button class="logout">Volver</button>
        </form>
    </div>

    <script src="../validaciones/funciones.js"></script>
</body>
</html>
