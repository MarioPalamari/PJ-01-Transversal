<?php
session_start();
include '../conexion/conexion.php';

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario'])) {
    echo "<script>alert('Usuario no autenticado'); window.location.href = 'login.php';</script>";
    exit;
}

$usuario = $_SESSION['usuario'];
$tableIds = $_POST['table_ids'] ?? [];

// Verificar que se seleccionaron mesas
if (empty($tableIds)) {
    echo "<script>alert('No seleccionó ninguna mesa'); window.history.back();</script>";
    exit;
}

// Obtener ID del usuario basado en el nombre de usuario
$sqlGetUserId = "SELECT user_id FROM tbl_users WHERE username = ?";
$stmtGetUserId = $conexion->prepare($sqlGetUserId);
$stmtGetUserId->bind_param("s", $usuario);
$stmtGetUserId->execute();
$result = $stmtGetUserId->get_result();
$userId = ($result->num_rows > 0) ? $result->fetch_assoc()['user_id'] : null;

if (!$userId) {
    echo "<script>alert('Error obteniendo usuario'); window.history.back();</script>";
    exit;
}

// Insertar el nuevo grupo de mesas
$sqlInsertGroup = "INSERT INTO tbl_table_groups (user_id) VALUES (?)";
$stmtInsertGroup = $conexion->prepare($sqlInsertGroup);
$stmtInsertGroup->bind_param("i", $userId);
$stmtInsertGroup->execute();
$groupId = $stmtInsertGroup->insert_id;

// Cambiar estado de las mesas a ocupadas y vincularlas con el grupo
foreach ($tableIds as $tableId) {
    $sqlUpdateTable = "UPDATE tbl_tables SET status = 'occupied' WHERE table_id = ?";
    $stmtUpdateTable = $conexion->prepare($sqlUpdateTable);
    $stmtUpdateTable->bind_param("i", $tableId);
    $stmtUpdateTable->execute();

    $sqlInsertGroupTable = "INSERT INTO tbl_group_tables (group_id, table_id) VALUES (?, ?)";
    $stmtInsertGroupTable = $conexion->prepare($sqlInsertGroupTable);
    $stmtInsertGroupTable->bind_param("ii", $groupId, $tableId);
    $stmtInsertGroupTable->execute();
}

echo "<script>alert('Mesas agrupadas y marcadas como ocupadas'); window.location.href = 'mesas.php';</script>";
?>
