<!-- Eleccion de los diferentes salones -->
<?php session_start(); 
include_once('../conexion/conexion.php');
?>

<form action="./cerrarSesion/logout.php" method="POST">
    <button>Cerrar sesion</button>
</form>