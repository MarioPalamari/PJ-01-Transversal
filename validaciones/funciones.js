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


// TERRAZA
// Función para abrir la ventana emergente
function ventana1() {
    document.getElementById("ventana-terraza").style.display = "block";
}

// Función para cerrar la ventana emergente
function cerrarventana1() {
    document.getElementById("ventana-terraza").style.display = "none";
}

// Cerrar la ventana si se hace clic fuera de él
window.onclick = function(event) {
    ventana = document.getElementById("ventana-terraza");
    if (event.target === ventana) {
        cerrarventana1();
    }
}

// SALONES
// Función para abrir la ventana emergente
function ventana2() {
    document.getElementById("ventana-salon").style.display = "block";
}

// Función para cerrar la ventana emergente
function cerrarventana2() {
    document.getElementById("ventana-salon").style.display = "none";
}

// Cerrar la ventana si se hace clic fuera de él
window.onclick = function(event) {
    ventana = document.getElementById("ventana-salon");
    if (event.target === ventana) {
        cerrarventana2();
    }
}

// VIP
// Función para abrir la ventana emergente
function ventana3() {
    document.getElementById("ventana-vip").style.display = "block";
}

// Función para cerrar la ventana emergente
function cerrarventana3() {
    document.getElementById("ventana-vip").style.display = "none";
}

// Cerrar la ventana si se hace clic fuera de él
window.onclick = function(event) {
    ventana = document.getElementById("ventana-vip");
    if (event.target === ventana) {
        cerrarventana3();
    }
}
