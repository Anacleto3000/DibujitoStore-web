<?php
session_start();



require_once __DIR__ . "/../config.php";

$conn = new mysqli(
    $host,
    $user,
    $password,
    $db
);

$busqueda = $_GET['busqueda'] ?? '';
$categoria_id=$_GET['categoria_id'] ?? '';

if(!empty($busqueda)){
    $sql = "SELECT p.*, 
            IFNULL(p.precio - (p.precio * o.porcentaje / 100), p.precio) AS precio_oferta,
            COALESCE(m.nombre, 'Sin definir') AS marca_nombre
            FROM productos p 
            LEFT JOIN ofertas o ON p.id = o.producto_id AND o.activo = 1
            LEFT JOIN marcas m ON p.marca_id = m.id
            WHERE p.nombre LIKE ?";
    $stmt = $conn->prepare($sql);

    $param = "%" . $busqueda . "%";
    $stmt->bind_param("s", $param);
    $stmt->execute();

}


elseif (!empty($categoria_id)) {
    
    $sql = "SELECT p.*, 
            IFNULL(p.precio - (p.precio * o.porcentaje / 100), p.precio) AS precio_oferta,
            COALESCE(m.nombre, 'Sin definir') AS marca_nombre
            FROM productos p 
            LEFT JOIN ofertas o ON p.id = o.producto_id AND o.activo = 1
            LEFT JOIN marcas m ON p.marca_id = m.id
            WHERE p.categoria_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $categoria_id);
    $stmt->execute();

}

$result = $stmt->get_result();



?>

<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <link rel="stylesheet" href="ResultadoProductos.css">
    <link rel="stylesheet" href="../carrito/VerCarrito.css">
    <link rel="stylesheet" href="../includes/header.css">


    <link href="https://fonts.googleapis.com/css2?family=Krona+One&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=AR+One+Sans:wght@400;500;700&display=swap" rel="stylesheet">
    
        <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>
    <?php include('../includes/header.php'); ?>

    <main>
        <div id="box_principal">
            <div id="Menu">
                <div>
                    <div id="container_titulo">
                        <h3>Filtrar productos</h3>
                    </div>
                    <div id="container_opciones">

                        <p>Ordenar Por :</p>
                        <p>Marca :</p>

                    </div>

                </div>

            </div>

            <div id="resultado_productos">
                <div id="contenedor_productos">



                    <?php while ($prod = $result->fetch_assoc()): ?>

                        <div class="producto">
                              
                            <a href="../DetallesProducto/DetallesProducto.php?id=<?= $prod['id'] ?>&categoria_id=<?= $prod['categoria_id'] ?>" class="producto-link">
                                    <div class="img_producto">
                                        <img
                                            src="../imagenes/<?= htmlspecialchars($prod['imagen']) ?>"
                                            alt="<?= htmlspecialchars($prod['nombre']) ?>">
                                    </div>
      
                                    <span class="marca-producto"><?= htmlspecialchars($prod['marca_nombre']) ?></span>
                                    <strong><?= htmlspecialchars($prod['nombre']) ?></strong><br>
                                    <div class="precios-producto">
                                        <?php if ($prod['precio'] > $prod['precio_oferta']): ?>
                                           <div class="container_precio_des">
                                               
                                            <span class="precio-original">S/ <?= number_format($prod['precio'], 2) ?> un</span>
                                             <span class="number_descuento">-<?= (($prod['precio']- $prod['precio_oferta'])/$prod['precio'])*100 ?> %</span>                                               
                                           </div>


                                        <?php endif; ?>
                                        <span class="precio-actual">S/ <?= number_format($prod['precio_oferta'], 2) ?> un</span>
                                    </div>
                                    
                                    <div id="container_buttons">
                                        <button class="btn-agregar-carrito" data-id="<?= $prod['id'] ?>">Agregar</button> 
                                        <img src="../iconos/corazon.png">
                                    </div>
 
                                    
                            </a>        

                        </div>




                    <?php endwhile; ?>

                </div>

            </div>
        </div>
    </main>


    <script src="../Javascript/carrito.js"></script>

</body>


</html>