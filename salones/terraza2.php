<?php
session_start();
include '../conexion/conexion.php';

// Función para convertir un número entero a un número romano
function romanNumerals($number) {
    $map = [
        'M' => 1000,
        'CM' => 900,
        'D' => 500,
        'CD' => 400,
        'C' => 100,
        'XC' => 90,
        'L' => 50,
        'XL' => 40,
        'X' => 10,
        'IX' => 9,
        'V' => 5,
        'IV' => 4,
        'I' => 1
    ];
    $result = '';
    foreach ($map as $roman => $int) {
        while ($number >= $int) {
            $result .= $roman;
            $number -= $int;
        }
    }
    return $result;
}

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario'])) {
    echo "<h6>Por favor, inicie sesión.</h6>";
    exit;
}

$usuario = $_SESSION['usuario'];

// Obtener ID del usuario basado en el nombre de usuario
$sqlGetUserId = "SELECT user_id FROM tbl_users WHERE username = ?";
$stmtGetUserId = $conexion->prepare($sqlGetUserId);
$stmtGetUserId->bind_param("s", $usuario);
$stmtGetUserId->execute();
$result = $stmtGetUserId->get_result();
$userId = ($result->num_rows > 0) ? $result->fetch_assoc()['user_id'] : null;

// Actualizar la ocupación o desocupación de una mesa
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && isset($_POST['tableId'])) {
    $tableId = $_POST['tableId'];
    $action = $_POST['action'];

    if ($action === 'occupy') {
        $sqlUpdateTable = "UPDATE tbl_tables SET status = 'occupied' WHERE table_id = ?";
        $stmtUpdateTable = $conexion->prepare($sqlUpdateTable);
        $stmtUpdateTable->bind_param("i", $tableId);
        $stmtUpdateTable->execute();

        $sqlInsertOccupation = "INSERT INTO tbl_occupations (table_id, user_id, start_time) VALUES (?, ?, CURRENT_TIMESTAMP)";
        $stmtInsertOccupation = $conexion->prepare($sqlInsertOccupation);
        $stmtInsertOccupation->bind_param("ii", $tableId, $userId);
        $stmtInsertOccupation->execute();
    } elseif ($action === 'free') {
        $sqlUpdateTable = "UPDATE tbl_tables SET status = 'free' WHERE table_id = ?";
        $stmtUpdateTable = $conexion->prepare($sqlUpdateTable);
        $stmtUpdateTable->bind_param("i", $tableId);
        $stmtUpdateTable->execute();

        $sqlEndOccupation = "UPDATE tbl_occupations SET end_time = CURRENT_TIMESTAMP WHERE table_id = ? AND end_time IS NULL";
        $stmtEndOccupation = $conexion->prepare($sqlEndOccupation);
        $stmtEndOccupation->bind_param("i", $tableId);
        $stmtEndOccupation->execute();
    }
    
    // Agrupación de mesas
    elseif ($action === 'group') {
        $sqlInsertGroup = "INSERT INTO tbl_table_groups (user_id) VALUES (?)";
        $stmtInsertGroup = $conexion->prepare($sqlInsertGroup);
        $stmtInsertGroup->bind_param("i", $userId);
        $stmtInsertGroup->execute();
        $groupId = $stmtInsertGroup->insert_id;

        $sqlInsertGroupTable = "INSERT INTO tbl_group_tables (group_id, table_id) VALUES (?, ?)";
        $stmtInsertGroupTable = $conexion->prepare($sqlInsertGroupTable);
        $stmtInsertGroupTable->bind_param("ii", $groupId, $tableId);
        $stmtInsertGroupTable->execute();
    }

    // Movimiento de mesas
    elseif ($action === 'move' && isset($_POST['newRoomId'])) {
        $newRoomId = $_POST['newRoomId'];
        $sqlMoveTable = "UPDATE tbl_tables SET current_room_id = ? WHERE table_id = ?";
        $stmtMoveTable = $conexion->prepare($sqlMoveTable);
        $stmtMoveTable->bind_param("ii", $newRoomId, $tableId);
        $stmtMoveTable->execute();
    }

    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

$sql = "SELECT table_id, status FROM tbl_tables WHERE table_id BETWEEN 6 AND 15";
$result = $conexion->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terraza II</title> 
    <link rel="stylesheet" href="../styles.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://fonts.googleapis.com/css2?family=Sancreek&display=swap" rel="stylesheet">
</head>
<body>
    <div class="container2">
        <div class="header">
            <h1>T e r r a z a    II</h1> <!-- Título actualizado -->
        </div>
        <div class="grid2">
            <?php
            // Generar HTML para cada mesa
            while ($row = $result->fetch_assoc()) {
                $tableId = $row['table_id'];
                $status = $row['status'];
                $romanTableId = romanNumerals($tableId); // Convertimos a números romanos
                $imgSrc = ($status === 'occupied') ? '../img/sombrillaRoja.webp' : '../img/sombrilla.webp';

                echo "
                <div class='table' id='mesa$tableId' onclick='openTableOptions($tableId, \"$status\", \"$romanTableId\")'>
                    <img id='imgMesa$tableId' src='$imgSrc' alt='Mesa $tableId'>
                    <p>Mesa $romanTableId</p>
                </div>

                <form id='formMesa$tableId' method='POST' style='display: none;'>
                    <input type='hidden' name='tableId' value='$tableId'>
                    <input type='hidden' name='action' id='action$tableId'>
                    <input type='hidden' name='newRoomId' id='newRoomId$tableId'>
                </form>
                ";
            }
            ?>

        </div>

        <button class="logout-button" onclick="logout()">Cerrar Sesión</button>
        <form action="../paginaPrincipal.php">
            <button class="logout">Volver</button>
        </form>
    </div>

    <script src="../validaciones/funcionesSalones.js"></script>

</body>
</html>
