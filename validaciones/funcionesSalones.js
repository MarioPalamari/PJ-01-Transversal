// Función para mostrar las opciones del modal
function openTableOptions(tableId, status, romanTableId, roomId) {
    const actions = [
        { label: status === 'free' ? 'Ocupar Mesa' : 'Desocupar Mesa', value: status === 'free' ? 'occupy' : 'free' },
        { label: 'Agrupar Mesas', value: 'group' },
        { label: 'Mover Mesa', value: 'move' }
    ];

    let optionsHtml = actions.map(action => 
        `<button onclick="submitAction(${tableId}, '${action.value}', ${roomId})"
                style="padding: 10px 20px; margin: 5px; background-color: #8A5021; color: white; 
                border: none; border-radius: 10px; cursor: pointer; width: 250px; text-align: center;">
            ${action.label}
        </button>`
    ).join('');

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

// Función para manejar la acción seleccionada y enviar el formulario
function submitAction(tableId, action, roomId) {
    if (action === 'group') {
        // Obtener todas las mesas disponibles
        fetch(`../procMesas/getAvailableTables.php?room Id=${roomId}`) // Pasar el roomId como parámetro
            .then(response => response.json())
            .then(tables => {
                const tableOptions = tables.map(table => 
                    `<div style="margin-bottom: 10px;">
                        <input type="checkbox" id="table${table.table_id}" value="${table.table_id}">
                        <label for="table${table.table_id}">Mesa ${table.roman_table_id}</label>
                    </div>`
                ).join('');

                Swal.fire({
                    title: 'Selecciona las mesas que deseas agrupar',
                    html: `<div style="max-height: 300px; overflow-y: auto;">${tableOptions}</div>`,
                    showCancelButton: true,
                    confirmButtonText: 'Agrupar Mesas',
                    preConfirm: () => {
                        const selectedTables = [];
                        tables.forEach(table => {
                            if (document.getElementById(`table${table.table_id}`).checked) {
                                selectedTables.push(table.table_id);
                            }
                        });
                        return selectedTables;
                    }
                }).then(result => {
                    if (result.isConfirmed) {
                        const selectedTables = result.value;
                        if (selectedTables.length > 0) {
                            groupTables(selectedTables);
                        }
                    }
                });
            });
    } else {
        // Si no es "Agrupar Mesas", se maneja el resto de las acciones
        const form = document.getElementById(`formMesa${tableId}`);
        document.getElementById(`action${tableId}`).value = action; // Asigna la acción seleccionada al campo hidden
        form.submit();
    }
}

// Función para agrupar las mesas seleccionadas
function groupTables(selectedTables) {
    const formData = new FormData();
    formData.append('action', 'group');
    formData.append('tables', JSON.stringify(selectedTables)); // Pasamos un JSON con las mesas seleccionadas

    fetch('../procMesas/groupTables.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire('Mesas agrupadas exitosamente');
            location.reload(); // Recargar para mostrar los cambios
        } else {
            Swal.fire('Error', 'No se pudieron agrupar las mesas', 'error');
        }
    });
}