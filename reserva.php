<!-- resultado.php -->
<?php
session_start(); // Iniciar sesión para acceder a los datos de la reserva

// Verificar si hay datos de reserva en la sesión
if (!isset($_SESSION['reserva'])) {
    header("Location: index.php"); // Redirigir si no hay datos
    exit();
}

$reserva = $_SESSION['reserva'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultado de la Reserva</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        form {
            max-width: 400px;
            margin: auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .correcto {
            color: green;
        }
        .incorrecto {
            color: red;
        }
        .vehiculo-imagen {
            max-width: 300px;
            margin-top: 20px;
        }
    </style>

</head>
<body>

<h2><?php echo $reserva['exito'] ? 'Reserva Válida' : 'Reserva No Válida'; ?></h2>

<?php if ($reserva['exito']): ?>
    <p>Reserva válida para:</p>
    <p><?php echo htmlspecialchars($reserva['nombre'] . ' ' . $reserva['apellido']); ?></p>
    <img src="<?php echo htmlspecialchars($reserva['imagen']); ?>" alt="Vehículo" class ="vehiculo-imagen">
<?php else: ?>
    <p>Datos introducidos para la reserva:</p>
    <p>Nombre: <span class="<?php if($reserva['nombreOK'] == "ok"){echo 'correcto';} else {echo 'incorrecto';}?>"><?php echo htmlspecialchars($reserva['nombre']);?></span></p>
    <p>Apellido: <span class="<?php if($reserva['apellidoOK'] == "ok"){echo 'correcto';} else {echo 'incorrecto';}?>"><?php echo htmlspecialchars($reserva['apellido']);?></span></p>
    <p>DNI: <span class="<?php if($reserva['dniOK'] == "ok"){echo 'correcto';} else {echo 'incorrecto';}?>"><?php echo htmlspecialchars($reserva['dni']);?></span></p>
    <p>Modelo de Coche: <span class="<?php if($reserva['dispoOK']){echo 'correcto';} else {echo 'incorrecto';}?>"><?php echo htmlspecialchars($reserva['modelo']);?></span></p>
    <p>Fecha inicio reserva: <span class="<?php if($reserva['fechaOK'] == true){echo 'correcto';} else {echo 'incorrecto';}?>"><?php echo htmlspecialchars($reserva['fechaInicio']);?></span></p>
    <p>Duración de la reserva: <span class="<?php if($reserva['duraOK'] == true){echo 'correcto';} else {echo 'incorrecto';}?>"><?php echo htmlspecialchars($reserva['duracion']);?> días</span></p>
    <p>Se encontraron los siguientes errores:</p>
    <ul>
        <?php foreach ($reserva['errores'] as $error): ?>
            <li><?php echo htmlspecialchars($error); ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<?php
// Limpiar la sesión después de mostrar el resultado
unset($_SESSION['reserva']);
?>

</body>
</html>