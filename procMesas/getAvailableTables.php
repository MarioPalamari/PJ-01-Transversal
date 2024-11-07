<?php
// Conexión a la base de datos
include '../conexion/conexion.php';

// Obtener el ID de la terraza desde el parámetro
if (isset($_GET['roomId'])) {
    $roomId = intval($_GET['roomId']); // Asegúrate de que el roomId es un entero válido

    // Consulta para obtener las mesas disponibles en esa terraza
    $sql = "SELECT table_id, status, roman_table_id FROM tbl_tables WHERE current_room_id = ? AND status = 'free'";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $roomId); // Vinculamos el ID de la terraza
    $stmt->execute();
    $result = $stmt->get_result();

    $tables = [];
    while ($row = $result->fetch_assoc()) {
        $tables[] = $row;
    }

    // Devolver los datos en formato JSON
    echo json_encode($tables);
} else {
    echo json_encode([]);
}
?>
