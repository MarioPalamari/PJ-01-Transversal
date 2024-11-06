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
            <!-- Mesa 1 -->
            <div class="table" id="mesa1" onclick="T1mesa1(1, 101)">
                <img id="imgMesa1" src="../img/sombrilla.webp" alt="Mesa 1">
                <p>mesa I</p>
            </div>
            <!-- Mesa 2 -->
            <div class="table" id="mesa2" onclick="T1mesa1(2, 101)">
                <img id="imgMesa2" src="../img/sombrilla.webp" alt="Mesa 2">
                <p>mesa II</p>
            </div>
            <!-- Mesa 3 -->
            <div class="table" id="mesa3" onclick="T1mesa1(3, 101)">
                <img id="imgMesa3" src="../img/sombrilla.webp" alt="Mesa 3">
                <p>mesa III</p>
            </div>
            <!-- Mesa 4 -->
            <div class="table" id="mesa4" onclick="T1mesa1(4, 101)">
                <img id="imgMesa4" src="../img/sombrilla.webp" alt="Mesa 4">
                <p>mesa IV</p>
            </div>
            <!-- Mesa 5 -->
            <div class="table" id="mesa5" onclick="T1mesa1(5, 101)">
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
