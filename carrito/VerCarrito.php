<?php
session_start();




require_once __DIR__ . "/../config.php";

$conn = new mysqli(
    $host,
    $user,
    $password,
    $db
);


$sql = "SELECT 
            dc.producto_id, 
            dc.cantidad, 
            p.nombre, 
            p.imagen, 
            IFNULL(p.precio - (p.precio * o.porcentaje / 100), p.precio) AS precio
        FROM detalle_carrito dc
        JOIN carrito c ON dc.carrito_id = c.id
        JOIN productos p ON dc.producto_id = p.id
        LEFT JOIN ofertas o ON p.id = o.producto_id AND o.activo = 1
        WHERE c.usuario_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $_SESSION['usuario_id']);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<p>🛒 El carrito está vacío</p>";
}



?>


<div id='container_padre'>
    <?php
    while ($row = $result->fetch_assoc()) {
    echo
    "

    
         <div class='item-carrito'>

                  <div class='box_primary'> 
                       <img src='../imagenes/{$row['imagen']}'  width='80px' height='80px'>
                           <div class='info-producto'> 
                               <span style='font-weight: bold;'>{$row['nombre']}</span>
                               <span style='font-weight: bold;' class='precio-producto' data-precio='{$row['precio']}'> S/ {$row['precio']}</span>
                           </div>
            
                  </div>
               
        
                  <div class='acciones-producto'>
                    <input type='number' min='1' value='{$row['cantidad']}' class='cantidad-producto' data-id='{$row['producto_id']}'>
                    <button class='btn-eliminar-producto' data-id='{$row['producto_id']}'>Eliminar</button>
                  </div>

         
         </div>



       
       ";
    }
    ?>

</div>





