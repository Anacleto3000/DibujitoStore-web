<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST['usuario'];
    $contrasena = $_POST['contrasena'];


    require_once __DIR__ . "/../config.php";
    
    $conn = new mysqli(
        $host,
        $user,
        $password,
        $db
    );

    if ($conn->connect_error) {
        die("Conexion falida: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("SELECT nombre, contrasena, id FROM usuarios WHERE nombre = ? AND contrasena = ?");
    $stmt->bind_param("ss", $usuario, $contrasena);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows === 1) {

        $fila = $result->fetch_assoc();
        $_SESSION["usuario_name"] = $fila["nombre"];
        $_SESSION["usuario_id"] = $fila["id"];

        header("Location: /PaginaPrincipal/VentanaProductos.php");
        exit;
    } else {
?>
        <div>
            <script>
                alert("Usuario o contraseña incorrectos")
            </script>

        </div>

<?php
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
    <title>Document</title>
    <link rel="stylesheet" href="LoginCliente.css">


    <link href="https://fonts.googleapis.com/css2?family=Krona+One&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=AR+One+Sans:wght@400;500;700&display=swap" rel="stylesheet">
</head>

<body>
    <div id="caja_principal">

        <div id="caja_secundaria">
            <div id="Titulo">
                <h2>Iniciar Sesion</h2>
            </div>
            <div id="box_input_datos">
                <form action="" method="post">
                    <input type="text" name="usuario" placeholder="Usuario">
                    <input type="password" name="contrasena" placeholder="Contraseña">

                    <div id="Container_Button">
                        <button type="submit">Iniciar Sesion</button>
                    </div>

                </form>

            </div>



            <div id="no_key">
                <p>Olvidaste tu contraseña</p>
            </div>


        </div>

        <div id="caja_terciaria">
            <div id="ini_alternativa">
                <p>Continuar con :</p>
                <img src="../iconos/image 22.png" alt="">
            </div>

            <div id="no_account">
                <p>No tienes una cuenta <a href="RegistroCliente.php">registrate</a></p>
            </div>
        </div>


    </div>
</body>

</html>