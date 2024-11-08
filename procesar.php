<?php

//Se incluye usuarios_y_coches.php
include 'usuarios_y_coches.php';

// Iniciar sesión para almacenar datos
session_start(); 

// Variables para recibir datos del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST"){
    $nombre = $_POST["nombre"];
    $apellido = $_POST["apellido"];
    $dni = $_POST["dni"];
    $modelo = $_POST["modelo"];
    $fechaInicio = $_POST["fechaInicio"];
    $duracion = $_POST["duracion"];
    // Comprobar y validar la información recibida en el formulario
    validarFormulario($nombre, $apellido, $dni, $modelo, $fechaInicio, $duracion);

    // Redirigir a la página de resultados de la reserva
    header("Location: reserva.php");
    exit();
}
// Función para validar la existencia del usuario con los datos de nombre, apellidos y dni proporcionados
function validarUsuario($nombre, $apellido, $dni) {
    global $nombre;
    foreach (USUARIOS as $usuario) {
        if ($usuario["nombre"] == $nombre && $usuario["apellido"] == $apellido && $usuario["dni"] == $dni) {
            return true;
        }
    }
    return false;
}

// Función para validar el DNI con el algoritmo
function letraDNI($dni) {
    $letra = substr($dni, -1);
    $numeroletra = (int)substr($dni, 0, -1);
    $letradni = substr("TRWAGMYFPDXBNJZSQVHLCKE",$numeroletra % 23,1);
    if ($letra != $letradni){
        return false;
    }
    return true;
}

// Función para validar la fecha de inicio de la reserva
function validarFechaInicio($fechaInicio) {
    // Casteamos la fecha de reserva a formato datetime y obtenemos la fecha actual en el mismo formato
    $fecha = strtotime($fechaInicio);
    $hoy = strtotime(date("Y-m-d"));
    // Comparamos fechas 
    if ($fecha < $hoy){
        return false;
    };
    return true;
}

// Función para validar la duración de la reserva
function validarDuracion($duracion) {
    if ($duracion >= 1 && $duracion <= 30){return true;}else{return false;}
}

// Función para validar la disponibilidad del coche
function validarDisponibilidad($modelo) {
    // declaramos variables globales para la función:
    global $coches;
    global $fechaInicio;
    global $duracion; 

    // Generamos la fecha de fin de reserva sumando la duración a la fecha de inicio y casteando
    $fechaFin = strtotime("$fechaInicio + $duracion");
    $fechaFin = date('Y-m-d', $fechaFin);

    foreach ($coches as $coche) {
        if ($coche["modelo"] == $modelo && !$coche["disponible"]) {
            if (($fechaInicio >= $coche['fecha_inicio'] && $fechaInicio <= $coche['fecha_fin']) || 
            ($fechaFin >= $coche['fecha_inicio'] && $fechaFin <= $coche['fecha_fin']) ||
            ($fechaInicio <= $coche['fecha_inicio'] && $fechaFin >= $coche['fecha_fin'])){
                return false;
            }
            return true;
        }
    }
    return true;
}

//Función para mostrar la imagen del coche en reservas exitosas
function imagenCoche($modelo){
    switch ($modelo) {
        case 'Lancia Stratos':
            return 'https://upload.wikimedia.org/wikipedia/commons/thumb/0/00/Lancia_Stratos_HF_001.JPG/640px-Lancia_Stratos_HF_001.JPG';
        case 'Ford Escort RS1800':
            return 'https://static.wikia.nocookie.net/forzamotorsport/images/a/a3/HOR_XB1_Ford_Escort_77.png';
        case 'Audi Quattro':
            return 'https://www.eventosmotor.com/wp-content/uploads/2020/10/audi_quattro_blog-premium-eventosmotor-4.jpg';
        case 'Subaru Impreza 555':
            return 'https://acnews.blob.core.windows.net/imgnews/medium/NAZ_a35482fdc00b45e9a481cfd79ad35e4c.jpg';
    }
}

//Función para validar el formulario devolviendo todos los errores del formulario
function validarFormulario($nombre, $apellido, $dni, $modelo, $fechaInicio, $duracion){
    //declaro una variable para enumerar los mensajes de error en una lista
    $mensajesError = [];
    $reservaOK = true;
    //Variables para llevar la propiedad correcto/incorrecto a la página de resultados
    $nombreOK = "";
    $apellidoOK = "";
    $dniOK = "";
    $fechaOK = true;
    $duraOK = true;
    $dispoOK = true;
    if (empty($nombre) || empty($apellido)) {
        $mensajesError[] = "El nombre y apellido no pueden estar vacíos.";
        $reservaOK = false;
        if (empty($nombre)){
            $nombreOK = "";
        } else {$nombreOK = "ok";}
        if (empty($apellido)){
            $apellidoOK = "";
        } else {$apellidoOK = "ok";}
    } 
    
    if (!letraDNI($dni)) {
        $mensajesError[] = "El DNI no es válido.";
        $reservaOK = false;
        $dniOK = false;
    } 

    if (!validarUsuario($nombre, $apellido, $dni)) {
        $mensajesError[] = "El usuario no existe.";
        $reservaOK = false;
        foreach (USUARIOS as $usuario) {
            if ($usuario["nombre"] == $nombre){
                $nombreOK = $nombreOK . "ok";
            } 
            if ($usuario["apellido"] == $apellido){
                $apellidoOK = $apellidoOK . "ok";
            } 
            if ($usuario["dni"] == $dni) {
                $dniOK = $dniOK . "ok";
            }
        }
    } 

    if (!validarFechaInicio($fechaInicio)) {
        $mensajesError[] = "La fecha de inicio debe ser posterior a la fecha actual.";
        $reservaOK = false;
        $fechaOK = false;
    } 

    if (!validarDisponibilidad($modelo)) {
        $mensajesError[] = "El coche no está disponible.";
        $reservaOK = false;
        $dispoOK = false;
    } 

    if (!validarDuracion($duracion)) {
        $mensajesError[] = "La duración debe ser un número entero entre 1 y 30 días.";
        $reservaOK = false;
        $duraOK = false;
    }

    if (empty($mensajesError)) {
        $mensajesError[] = "La reserva se ha realizado correctamente.";
        $reservaOK = true;
    }
    $_SESSION['reserva'] = [
        'nombre' => $nombre,
        'apellido' => $apellido,
        'dni' => $dni,
        'modelo' => $modelo,
        'fechaInicio' => $fechaInicio,
        'duracion' => $duracion,
        'exito' => $reservaOK,
        'errores' => $mensajesError,
        'nombreOK' => $nombreOK,
        'apellidoOK' => $apellidoOK,
        'dniOK' => $dniOK,
        'fechaOK' => $fechaOK,
        'duraOK' => $duraOK,
        'dispoOK' => $dispoOK,
        'imagen' => $reservaOK? imagenCoche($modelo) : null // Solo se muestra si es exitoso
    ];
}
?>