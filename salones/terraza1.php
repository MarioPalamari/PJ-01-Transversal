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

    // Proceso de agrupación de mesas
elseif ($action === 'group' && isset($_POST['selectedTables'])) {
    $selectedTables = $_POST['selectedTables'];

    // Crear un nuevo grupo
    $sqlInsertGroup = "INSERT INTO tbl_table_groups (user_id) VALUES (?)";
    $stmtInsertGroup = $conexion->prepare($sqlInsertGroup);
    $stmtInsertGroup->bind_param("i", $userId);
    $stmtInsertGroup->execute();
    $groupId = $stmtInsertGroup->insert_id;

    // Agregar cada mesa al grupo creado
    $sqlInsertGroupTable = "INSERT INTO tbl_group_tables (group_id, table_id) VALUES (?, ?)";
    $stmtInsertGroupTable = $conexion->prepare($sqlInsertGroupTable);
    foreach ($selectedTables as $tableId) {
        $stmtInsertGroupTable->bind_param("ii", $groupId, $tableId);
        $stmtInsertGroupTable->execute();

        // Actualizar el estado de la mesa a "occupied" (opcional)
        $sqlUpdateTable = "UPDATE tbl_tables SET status = 'occupied' WHERE table_id = ?";
        $stmtUpdateTable = $conexion->prepare($sqlUpdateTable);
        $stmtUpdateTable->bind_param("i", $tableId);
        $stmtUpdateTable->execute();
    }
}

    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Consultar el estado actual de cada mesa en la terraza
$sql = "SELECT table_id, status FROM tbl_tables WHERE room_id = 1";
$result = $conexion->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terraza I</title>
    <link rel="stylesheet" href="../styles.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://fonts.googleapis.com/css2?family=Sancreek&display=swap" rel="stylesheet">
</head>
<body>
    <div class="container2">
        <div class="header">
            <h1>T e r r a z a    I</h1>
        </div>
        <div class="grid">
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

    <script>
function openTableOptions(tableId, status, romanTableId) {
    const actions = [
        { label: status === 'free' ? 'Ocupar Mesa' : 'Desocupar Mesa', value: status === 'free' ? 'occupy' : 'free' },
        { label: 'Seleccionar Mesa para Agrupar', value: 'selectForGroup' },
        { label: 'Mover Mesa', value: 'move' }
    ];

    let optionsHtml = actions.map(action => `
        <button onclick="submitAction(${tableId}, '${action.value}')" 
                style="padding: 10px 20px; margin: 5px; background-color: #8A5021; color: white; 
                border: none; border-radius: 10px; cursor: pointer; width: 250px; text-align: center;">
            ${action.label}
        </button>
    `).join('');

    Swal.fire({
        title: `<h2 style="color: white; font-family: 'Sancreek', cursive;">Mesa ${romanTableId}</h2>`,
        html: `<div style="display: flex; flex-direction: column; align-items: center;">${optionsHtml}</div>`,
        showConfirmButton: false,
        showCancelButton: true,
        cancelButtonText: '<span>Cancelar</span>',
        customClass: {
            popup: 'custom-swal-popup',
            title: 'custom-swal-title',
            content: 'custom-swal-content'
        },
        background: 'rgba(210, 180, 140, 0.8)',  // Fondo marrón claro menos transparente
        backdrop: 'rgba(0, 0, 0, 0.5)'
    });
}


// Cargar mesas libres en el modal
function fetchFreeTables() {
    fetch('fetch_free_tables.php') // Crear un archivo PHP que devuelva mesas libres
        .then(response => response.json())
        .then(data => {
            const container = document.getElementById('groupTablesContainer');
            container.innerHTML = data.tables.map(table =>
                `<label style="margin: 5px;">
                    <input type="checkbox" name="groupTableCheckbox" value="${table.table_id}">
                    Mesa ${table.roman_number}
                </label>`
            ).join('');
        })
        .catch(error => console.error('Error fetching tables:', error));
}

// Enviar acción de agrupación
function submitGroupAction(tableIds) {
    fetch('group_tables_action.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ tableIds: tableIds })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            tableIds.forEach(tableId => {
                document.getElementById(`imgMesa${tableId}`).src = '../img/sombrillaRoja.webp';
            });
            Swal.fire('Éxito', 'Mesas agrupadas y marcadas como ocupadas.', 'success');
        } else {
            Swal.fire('Error', 'No se pudo agrupar las mesas.', 'error');
        }
    })
    .catch(error => console.error('Error grouping tables:', error));
}
</script>
</body>
</html>
