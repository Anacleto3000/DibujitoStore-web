<?php

session_start();



require_once __DIR__ . "/../config.php";

$conn = new mysqli(
    $host,
    $user,
    $password,
    $db
);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

if (!isset($_SESSION['usuario_id'])) {
    echo "Debes iniciar sesión";
    exit;
}

if (!isset($_POST['producto_id'])) {
    echo "Producto inválido";
    exit;
}

$usuario_id  = $_SESSION['usuario_id'];
$producto_id = $_POST['producto_id'];



$sql = "SELECT id FROM carrito WHERE usuario_id = $usuario_id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $carrito_id = $row['id'];
} else {

    $conn->query("INSERT INTO carrito (usuario_id) VALUES ($usuario_id)");
    $carrito_id = $conn->insert_id;
}


$sql = "SELECT id, cantidad FROM detalle_carrito 
        WHERE carrito_id = $carrito_id AND producto_id = $producto_id";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "El producto ya está en el carrito";
}
else {

    $conn->query(
        "INSERT INTO detalle_carrito (carrito_id, producto_id, cantidad)
         VALUES ($carrito_id, $producto_id, 1)"
         
    );
    echo "Producto agregado al carrito";
}


