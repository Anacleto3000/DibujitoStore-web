<?php
session_start();
if (!isset($_GET['id'])) {
    die("Producto no encontrado");
}

$id = $_GET['id'];
$categoria_id = $_GET['categoria_id'];



require_once __DIR__ . "/../config.php";

$conn = new mysqli(
    $host,
    $user,
    $password,
    $db
);

$stmt = $conn->prepare("
    SELECT 
        p.*, 
        IFNULL(p.precio - (p.precio * o.porcentaje / 100), p.precio) AS precio_oferta,
        COALESCE(m.nombre, 'Sin definir') AS marca_nombre
    FROM productos p 
    LEFT JOIN ofertas o ON p.id = o.producto_id AND o.activo = 1 
    LEFT JOIN marcas m ON p.marca_id = m.id
    WHERE p.id = ?
");
$stmt->bind_param("i", $id);
$stmt->execute();

$result = $stmt->get_result();
$producto = $result->fetch_assoc();


$sql_similares = "SELECT p.*, 
                  IFNULL(p.precio - (p.precio * o.porcentaje / 100), p.precio) AS precio_oferta,
                  COALESCE(m.nombre, 'Sin definir') AS marca_nombre
                  FROM productos p
                  LEFT JOIN ofertas o ON p.id = o.producto_id AND o.activo = 1
                  LEFT JOIN marcas m ON p.marca_id = m.id 
                  WHERE p.categoria_id = ? LIMIT 4";
$stmt_similares = $conn->prepare($sql_similares);
$stmt_similares->bind_param("i", $categoria_id);
$stmt_similares->execute();
$result_similares = $stmt_similares->get_result();




if (!$producto) {
    die("Producto no existe");
}



?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="DetallesProducto.css?v=<?php echo time(); ?>">



    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Krona+One&display=swap" rel="stylesheet">



    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=AR+One+Sans:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../carrito/VerCarrito.css">
    <link rel="stylesheet" href="../includes/header.css">
    
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
</head>

<body>
    <?php include('../includes/header.php'); ?>

    <main>
        <div id="container_principal">
            <div id="container_secundario">

                <div id="imagen_producto">
                    <img src="../imagenes/<?= $producto['imagen'] ?>">
                </div>
                
                <div id="Info_producto">
                    
                    <div id="container_descripcion_producto">
                        <h2>Descripcion</h2>
                        <p><?= $producto['descripcion'] ?></p>
                    </div>

                </div>


            </div>
                <div id="box_info_and_options">
            
                     <div id="Textbox_producto">
                         
                         <div id="primary_box">
                             
                               
                            <div id="container_producto_superior">
                                <span class="marca-producto-principal"><?= htmlspecialchars($producto['marca_nombre']) ?></span>
                                
                                <div id="extra_container">
                                    <p><?= $producto['nombre'] ?></p>
                                    <img src="../iconos/corazon.png">
                                </div>
                                
                                
                                    <div id="container_box">
                                        <div id="info_precio">
                                            <?php if ($producto['precio'] > $producto['precio_oferta']): ?>
                                                <p class="precio-original-main">S/ <?= number_format($producto['precio'], 2) ?> </p>
                                            <?php endif; ?>
                                            <p class="precio-actual-main">S/ <?= number_format($producto['precio_oferta'], 2) ?> </p>
                                        </div>
                                    
                                        <div id="info_stock">
                                            <p>Quedan menos de <?= $producto['stock'] ?> unidades disponibles</p>
                                        </div>
                                    </div>
                                    
                                    <div id="container_button">
                                        <button class="btn-agregar-carrito" type="submit" data-id="<?= $producto['id'] ?>">Agregar al carrito</button>
                                    </div>
                                    
                                 
        
                            </div>
                            
        
        
        
        
        
                         </div>
                         
                         <div id="container_methods_pago">
                             <h4>Metodos de pago</h4>
                             
                             <div id="image-icons">
                                  <img src="../iconos/yape-logo-fondo-transparente.png">
                                  <img src="../iconos/Visa_Inc._logo_(2005–2014).png">
                                  <img src="../iconos/MasterCard_Logo.svg.png">
                                  
                             </div>
                         </div>
                         
        
                         
        
        
        
                    </div>
                    
                    <div id="container_compartir">
                         <p>Compartir en:</p>
                         <div>
                            <i class="bi bi-twitter-x"></i>
                             <i class="bi bi-facebook"></i>
                             <i class="bi bi-whatsapp"></i>
                         </div>

                    </div>
                    
                </div>




        </div>

        <div id=container_productos_similares>
                <div id="title_productos_similares">
                    <p>Productos Similares</p>
                </div>
                <div id="container_productos">
                    <?php while($producto_similar = $result_similares->fetch_assoc()): ?>
                    <a href="DetallesProducto.php?id=<?= $producto_similar['id'] ?>&categoria_id=<?= $producto_similar['categoria_id'] ?>" class="enlace_similar">
                        <div class="producto_similar">
                            <div class="img_container_similar">
                                <img src="../imagenes/<?= $producto_similar['imagen'] ?>" alt="<?= htmlspecialchars($producto_similar['nombre']) ?>">
                            </div>
                            <span class="marca-producto"><?= htmlspecialchars($producto_similar['marca_nombre']) ?></span>
                            <p class="similar_nombre"><?= $producto_similar['nombre'] ?></p>
                            <div class="precios-producto">
                                <?php if ($producto_similar['precio'] > $producto_similar['precio_oferta']): ?>
                                    <span class="precio-original">S/ <?= number_format($producto_similar['precio'], 2) ?> un</span>
                                <?php endif; ?>
                                <span class="precio-actual">S/ <?= number_format($producto_similar['precio_oferta'], 2) ?> un</span>
                            </div>
                        </div>
                    </a>
                    <?php endwhile; ?>
                    
                </div>
           
        </div>

        <!-- Footer agregado -->
        <footer class="footer-seguro">
            <div class="footer-contenedor">
                <div class="footer-columna">
                    <h3>Enlaces</h3>
                    <a href="#">Inicio</a>
                    <a href="#">Productos</a>
                    <a href="#">Contacto</a>
                </div>

                <div class="footer-columna">
                    <h3>Nosotros</h3>
                    <a href="../Presentacion/index.html">Quienes somos</a>
                    <a href="#">Misión</a>
                    <a href="#">Visión</a>
                </div>

                <div class="footer-columna">
                    <h3>Síguenos</h3>
                    <div class="footer-redes">
                        <a href="#"><img src="../iconos/facebook.png" alt=""></a>
                        <a href="#"><img src="../iconos/instagram.png" alt=""></a>
                        <a href="#"><img src="../iconos/twitter.png" alt=""></a>
                    </div>
                </div>
            </div>

            <p class="footer-copy">© 2024 Jeap - Todos los derechos reservados</p>
        </footer>








    </main>

<script src="../Javascript/carrito.js"></script>
</body>


</html>