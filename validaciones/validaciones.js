function validaNombre(){

    let nombre = document.getElementById("nombre").value; 
    let error_nombre= document.getElementById("error_nombre"); 

    if ( nombre == null || nombre.length == 0 ) {
        error_nombre.innerHTML = "El nombre está vacio";
        return false;

    } else if(!isNaN(nombre)) {
        error_nombre.innerHTML = "El campo no debe contener números"; 
        return false;

    } else if (nombre.length < 3) { 
        error_nombre.innerHTML = "El nombre es demasiado corto";
        return false;

    }  else {
        error_nombre.innerHTML = "";
        return true;
    }
}
function validaContraseña() {

    let contraseña = document.getElementById("contraseña").value;
    let error_contraseña = document.getElementById("error_contraseña"); 

    if(contraseña == null || contraseña.length == 0 || /^\s+$/.test(contraseña)){ 
        error_contraseña.innerHTML = "Este campo no puede estar vacío."
        return false;

    } else if(contraseña.length < 6) {
        error_contraseña.innerHTML = "La contraseña debe contener mas de 6 carácteres."
        return false;

    } else if (!(/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]+$/).test(contraseña)){ 
        error_contraseña.innerHTML = "La contraseña debe contener al menos un número y una letra."
        return false;

    } else {
        error_contraseña.innerHTML = "";
        return true;
    }
}

function validaGeneral() {
    return validaNombre() && validaContraseña();
    
}