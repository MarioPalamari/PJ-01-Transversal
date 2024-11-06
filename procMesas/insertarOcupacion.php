// insertarOcupacion.php
<?php
// Incluir la conexión a la base de datos
include_once('../conexion/conexion.php');

// Función para insertar la ocupación de la mesa
function insertarOcupacion($table_id, $user_id, $start_time) {
    global $pdo;  // Usamos la variable $pdo definida en db.php

    // Preparar la consulta SQL para insertar la ocupación
    $sql = "INSERT INTO tbl_occupations (table_id, user_id, start_time) VALUES (:table_id, :user_id, :start_time)";
    
    // Preparar la declaración
    $stmt = $pdo->prepare($sql);
    
    // Ejecutar la declaración con los valores proporcionados
    $stmt->execute([':table_id' => $table_id, ':user_id' => $user_id, ':start_time' => $start_time]);
    
    return true;
}

// Verificar si se han recibido los datos
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los datos de la solicitud POST
    $data = json_decode(file_get_contents("php://input"), true);

    if (isset($data['table_id']) && isset($data['user_id']) && isset($data['start_time'])) {
        $table_id = $data['table_id'];
        $user_id = $data['user_id'];
        $start_time = $data['start_time'];

        // Llamar a la función para insertar la ocupación
        if (insertarOcupacion($table_id, $user_id, $start_time)) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al registrar la ocupación']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
    }
}
?>
