<?php
// Formulario. Se incluye procesar.php que a su vez incluye usuarios_y_coches.php
include 'procesar.php';
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Reserva de Vehículo</title>
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
        label {
            margin-top: 10px;
            display: block;
        }
        input, select {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            margin-top: 15px;
            padding: 10px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>

<h2>Reserva de Vehículo</h2>

<form method="post">
    <label for="nombre">Nombre:</label>
    <input type="text" id="nombre" name="nombre"><br><br>
    <label for="apellido">Apellido:</label>
    <input type="text" id="apellido" name="apellido"><br><br>
    <label for="dni">DNI:</label>
    <input type="text" id="dni" name="dni"><br><br>
    <label for="modelo">Modelo de coche:</label>
    <!--El select para los vehículos, llama con con un foreach al array de coches para mostrar los modelos disponibles-->
    <select id="modelo" name="modelo">
        <?php foreach ($coches as $coche) { ?>
            <option value="<?php echo $coche["modelo"]; ?>"><?php echo $coche["modelo"]; ?></option>
        <?php } ?>
    </select><br><br>
    <label for="fechaInicio">Fecha de inicio:</label>
    <input type="date" id="fechaInicio" name="fechaInicio"><br><br>
    <label for="duracion">Duración (días):</label>
    <input type="number" id="duracion" name="duracion"><br><br>
    <button type="submit">Reservar</button>
</form>

</body>
</html>