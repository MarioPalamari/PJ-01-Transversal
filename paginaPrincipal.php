<!-- Eleccion de los diferentes salones -->
<?php session_start(); 
include('./conexion/conexion.php');

if (!isset($_SESSION['usuario'])) {
    echo "<h6>Por favor, inicie sesión.</h6>";
    exit;
}

?>


<form action="./cerrarSesion/logout.php" method="POST">
    <button>Cerrar sesion</button>
</form>