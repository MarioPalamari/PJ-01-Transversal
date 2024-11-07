<?php
session_start();
include '../conexion/conexion.php';

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario'])) {
    echo "<h6>Por favor, inicie sesión.</h6>";
    exit;
}

// Obtener los datos del formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['mainTableId']) && isset($_POST['tables']) && isset($_POST['roomId'])) {
    $mainTableId = $_POST['mainTableId'];
    $selectedTables = $_POST['tables'];
    $roomId = $_POST['roomId'];
    $userId = $_SESSION['usuario'];

    // Crear un grupo de mesas
    $sqlInsertGroup = "INSERT INTO tbl_table_groups (user_id, status) VALUES (?, 'active')";
    $stmtInsertGroup = $conexion->prepare($sqlInsertGroup);
    $stmtInsertGroup->bind_param("i", $userId);
    $stmtInsertGroup->execute();
    $groupId = $stmtInsertGroup->insert_id;

    // Insertar las mesas seleccionadas en el grupo
    foreach ($selectedTables as $tableId) {
        $sqlInsertGroupTable = "INSERT INTO tbl_group_tables (group_id, table_id) VALUES (?, ?)";
        $stmtInsertGroupTable = $conexion->prepare($sqlInsertGroupTable);
        $stmtInsertGroupTable->bind_param("ii", $groupId, $tableId);
        $stmtInsertGroupTable->execute();
    }

    // Actualizar el campo `current_room_id` de las mesas agrupadas
    $sqlUpdateTables = "UPDATE tbl_tables SET current_room_id = ? WHERE table_id IN (" . implode(",", array_map('intval', $selectedTables)) . ")";
    $stmtUpdateTables = $conexion->prepare($sqlUpdateTables);
    $stmtUpdateTables->bind_param("i", $roomId);
    $stmtUpdateTables->execute();

    // Mensaje de éxito
    echo "<script>
            alert('Mesas agrupadas exitosamente');
            window.location.href = 'terraza2.php'; // Redirige a la terraza actual
          </script>";
} else {
    echo "<h6>Error en la agrupación de mesas</h6>";
}
?>
