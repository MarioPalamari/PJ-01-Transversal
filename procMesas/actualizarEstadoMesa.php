// actualizarEstadoMesa.php
<?php
// Incluir la conexión a la base de datos
include_once('../conexion/conexion.php');
// Función para actualizar el estado de la mesa
function actualizarEstadoMesa($table_id, $status) {
    global $pdo;  // Usamos la variable $pdo definida en db.php

    // Validar los parámetros
    if (!in_array($status, ['free', 'occupied'])) {
        return false;
    }

    // Preparar la consulta SQL para actualizar el estado de la mesa
    $sql = "UPDATE tbl_tables SET status = :status WHERE table_id = :table_id";
    
    // Preparar la declaración
    $stmt = $pdo->prepare($sql);
    
    // Ejecutar la declaración con los valores proporcionados
    $stmt->execute([':status' => $status, ':table_id' => $table_id]);
    
    return true;
}

// Verificar si se han recibido los datos
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los datos de la solicitud POST
    $data = json_decode(file_get_contents("php://input"), true);

    if (isset($data['table_id']) && isset($data['status'])) {
        $table_id = $data['table_id'];
        $status = $data['status'];

        // Llamar a la función para actualizar el estado de la mesa
        if (actualizarEstadoMesa($table_id, $status)) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Estado de la mesa no válido']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
    }
}
?>
