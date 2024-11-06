function logout() {
    Swal.fire({
        title: '¿Estás seguro?',
        text: "¿Seguro que quieres cerrar sesión?",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, cerrar sesión',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = 'cerrarSesion/logout.php';
        }
    });
}
function logout1() {
    Swal.fire({
        title: '¿Estás seguro?',
        text: "¿Seguro que quieres cerrar sesión?",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, cerrar sesión',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = '../cerrarSesion/logout.php';
        }
    });
}

// Función para manejar la ocupación de la mesa
function T1mesa1() {
    // ID del camarero logueado (esto puede ser dinámico según el sistema de autenticación)
    const user_id = 1;  // Esto debe ser reemplazado por el ID real del camarero logueado
    const table_id = 1;  // ID de la mesa (esto también debe ser dinámico)

    // Mostrar el SweetAlert para preguntar si se quiere ocupar o liberar la mesa
    Swal.fire({
        title: '¿Vas a ocupar la mesa?',
        showDenyButton: true,
        showCancelButton: true,
        confirmButtonText: 'Ocupar',
        denyButtonText: 'Liberar',
    }).then((result) => {
        if (result.isConfirmed) {
            // Si el usuario selecciona "Ocupar", actualiza el estado y registra la ocupación

            // Actualizar el estado de la mesa a "ocupada"
            fetch('actualizarEstadoMesa.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ table_id: table_id, status: 'occupied' }),
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Registrar la ocupación de la mesa
                    const start_time = new Date().toISOString();  // Fecha y hora de inicio
                    fetch('insertarOcupacion.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({ table_id: table_id, user_id: user_id, start_time: start_time })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire('Mesa ocupada', '', 'success');
                        } else {
                            Swal.fire('Error al registrar la ocupación', '', 'error');
                        }
                    });
                } else {
                    Swal.fire('Error al actualizar el estado de la mesa', '', 'error');
                }
            });
        } else if (result.isDenied) {
            // Si el usuario selecciona "Liberar", actualiza el estado a "libre"
            fetch('actualizarEstadoMesa.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ table_id: table_id, status: 'free' }),
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire('Mesa liberada', '', 'success');
                } else {
                    Swal.fire('Error al liberar la mesa', '', 'error');
                }
            });
        }
    });
}


function actualizarMesa(idMesa, idCamarero, fecha, estado) {
    // Actualizar el estado de la mesa en la base de datos
    const datosMesa = {
        table_id: idMesa,
        status: estado
    };

    fetch('/actualizarEstadoMesa', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(datosMesa)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Guardar la ocupación si la mesa fue ocupada
            if (estado === 'occupied') {
                const datosOcupacion = {
                    table_id: idMesa,
                    user_id: idCamarero,   // Aquí pasamos el id del camarero
                    start_time: fecha
                };

                // Insertar ocupación en la base de datos
                fetch('/insertarOcupacion', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(datosOcupacion)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        console.log('Ocupación registrada correctamente');
                    } else {
                        console.error('Error al registrar la ocupación:', data.message);
                    }
                })
                .catch(error => {
                    console.error('Error en la solicitud de ocupación:', error);
                });
            } else {
                console.log('Mesa liberada correctamente');
            }
        } else {
            console.error('Error al actualizar el estado de la mesa:', data.message);
        }
    })
    .catch(error => {
        console.error('Error en la solicitud de actualización:', error);
    });
}
