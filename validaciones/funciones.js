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

const mesaStatus = {
    1: false, // false: libre, true: ocupada
    2: false,
    3: false,
    4: false,
    5: false
};

// Función para manejar el clic en cada mesa
function handleClick(mesaId) {
    if (mesaStatus[mesaId]) {
        // Si la mesa está ocupada, muestra opción de liberar
        Swal.fire({
            title: `Mesa ${mesaId} está ocupada`,
            text: "¿Quieres liberarla?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Liberar",
            cancelButtonText: "Cancelar"
        }).then((result) => {
            if (result.isConfirmed) {
                // Cambiar estado a libre y actualizar la imagen a sombrilla normal
                mesaStatus[mesaId] = false;
                document.getElementById(`imgMesa${mesaId}`).src = "../img/sombrilla.webp";
            }
        });
    } else {
        // Si la mesa está libre, muestra opción de ocupar
        Swal.fire({
            title: `Mesa ${mesaId} está libre`,
            text: "¿Quieres ocuparla?",
            icon: "info",
            showCancelButton: true,
            confirmButtonText: "Ocupar",
            cancelButtonText: "Cancelar"
        }).then((result) => {
            if (result.isConfirmed) {
                // Cambiar estado a ocupada y actualizar la imagen a sombrilla roja
                mesaStatus[mesaId] = true;
                document.getElementById(`imgMesa${mesaId}`).src = "../img/sombrillaRoja.webp";
            }
        });
    }
}
