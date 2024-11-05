<?php
session_start();
include_once('../conexion/conexion.php');

$usuario = mysqli_real_escape_string($conexion, $_POST['usuario']);
$password = mysqli_real_escape_string($conexion, $_POST['password']);

try{
    $sql = "SELECT nombre_camarero, password_camarero FROM tbl_camareros WHERE nombre_camarero = ?";
    $stmt = mysqli_prepare($conexion, $sql);
    mysqli_stmt_bind_param($stmt, "s", $usuario);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

    // Si el usuario existe, valido la contraseña
    if ($row && password_verify($password, $row['password_camarero'])) {
        $_SESSION['nombre_camarero'] = $row['nombre_camarero'];
        header('Location: ../paginaPrincipal.php');  
        exit();
    } else {
        header('Location: ../index.php?error=1');
        exit();
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>

    