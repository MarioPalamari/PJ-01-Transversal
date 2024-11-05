<?php
session_start();
include_once '../conexion/conexion.php';

$usuario = mysqli_real_escape_string($conexion, $_POST['usuario']); 
$password = mysqli_real_escape_string($conexion, $_POST['password']);
$_SESSION['usuario'] = $usuario;

try {
    $sql = "SELECT username, pwd FROM tbl_users WHERE username = ?";
    $stmt = mysqli_prepare($conexion, $sql);
    mysqli_stmt_bind_param($stmt, "s", $usuario);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

    // Si el usuario existe, validar la contraseña
    if ($row && password_verify($password, $row['pwd'])) {
        $_SESSION['username'] = $row['username'];
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
