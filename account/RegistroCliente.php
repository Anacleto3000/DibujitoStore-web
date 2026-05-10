<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $telefono = $_POST['telefono'];
    $correo = $_POST['correo'];
    $dni = $_POST['dni'];


    require_once __DIR__ . "/../config.php";
    
    $conn = new mysqli(
        $host,
        $user,
        $password,
        $db
    );

    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("INSERT INTO usuarios (nombre, contrasena, telefono, correo, dni) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $nombre, $apellido, $telefono, $correo, $dni);

    if ($stmt->execute()) {
        echo "Registro exitoso";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>




<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>

    <link rel="stylesheet" href="RegistroCliente.css">

    <link href="https://fonts.googleapis.com/css2?family=Krona+One&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=AR+One+Sans:wght@400;500;700&display=swap" rel="stylesheet">
</head>

<body>
    <div id="caja_principal">

        <div id="caja_secundaria">

            <div id="Titulo">
                <h2>Crear Cuenta</h2>
            </div>

            <div id="box_input_datos">
                <form action="" method="post">
                    <input type="text" name="nombre" placeholder="Nombre">
                    <input type="text" name="apellido" placeholder="Contraseña">
                    <input type="text" name="telefono" placeholder="Teléfono">
                    <input type="email" name="correo" placeholder="Correo electrónico">
                    <input type="text" name="dni" placeholder="DNI">

                    <div id="Container_Button">
                        <button type="submit">Registrarse</button>
                    </div>
                </form>

            </div>



        </div>

        <div id="caja_terciaria">
            <div id="no_account">
                <p>¿Ya tienes una cuenta? <a href="LoginCliente.php">Inicia sesión</a></p>
            </div>
        </div>

    </div>
</body>

</html>