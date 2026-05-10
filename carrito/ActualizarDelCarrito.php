<?php
session_start();



require_once __DIR__ . "/../config.php";

$conn = new mysqli(
    $host,
    $user,
    $password,
    $db
);

if (!isset($_POST['producto_id']) || !isset($_POST['cantidad']) || !isset($_SESSION['usuario_id'])) {
    echo "Datos inválidos";
    exit;
}

$producto_id = $_POST['producto_id'];
$cantidad = $_POST['cantidad'];
$usuario_id = $_SESSION['usuario_id'];

$sql = "UPDATE detalle_carrito dc
        JOIN carrito c ON dc.carrito_id = c.id
        SET dc.cantidad = ?
        WHERE c.usuario_id = ? AND dc.producto_id = ?";


$stmt = $conn->prepare($sql);
$stmt->bind_param("iii", $cantidad, $usuario_id, $producto_id);
$stmt->execute();
