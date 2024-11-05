<?php
session_start();
include_once('../conexion/conexion.php'); 

$usuario = $_POST['usuario'];
$password = $_POST['password'];
$_SESSION['usuario'] = $usuario;

$sql = "SELECT username FROM tbl_users WHERE username = ? AND pwd = ?";
$stmt = mysqli_prepare($conexion, $sql);
mysqli_stmt_bind_param($stmt, "ss", $usuario, $password); 
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) > 0) {
    $_SESSION['username'] = $usuario; 
    header('Location: ../paginaPrincipal.php');
    exit();
} else {
    header('Location: ../index.php?error=1'); 
    exit();
}
?>
