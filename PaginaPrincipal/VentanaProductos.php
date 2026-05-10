<?php
session_start();

?>


<?php

// Identico a driverManager.GetConnection( )


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



// SOLO 2 ANUNCIOS ACTIVOS POR TENER LIMIT 2

$sql = "SELECT imagen FROM anuncios WHERE activo = 1 LIMIT 2";

//Identico a executeQuery(SqlText) devolviendo un resultset como en java (jdbc)
$result_anuncios = $conn->query($sql);


$sql_promociones = "SELECT imagen FROM promociones WHERE activo = 1 LIMIT 6";
$result_promociones = $conn->query($sql_promociones);

$sql_productos = "SELECT 
    p.*, 
    IFNULL(p.precio - (p.precio * o.porcentaje / 100), p.precio) AS precio_oferta,
    COALESCE(m.nombre, 'Sin definir') AS marca_nombre
FROM productos p 
LEFT JOIN ofertas o ON p.id = o.producto_id AND o.activo = 1
LEFT JOIN marcas m ON p.marca_id = m.id
LIMIT 12";

$result_productos = $conn->query($sql_productos);



$sql_categotias = "SELECT * FROM categorias LIMIT 7";
$result_categorias = $conn->query($sql_categotias);

$sql_productos_tecno = "SELECT 
    p.*, 
    IFNULL(p.precio - (p.precio * o.porcentaje / 100), p.precio) AS precio_oferta,
    COALESCE(m.nombre, 'Sin definir') AS marca_nombre
FROM productos p 
LEFT JOIN ofertas o ON p.id = o.producto_id AND o.activo = 1
LEFT JOIN marcas m ON p.marca_id = m.id
WHERE p.categoria_id = 7 LIMIT 5";
$result_productos_tecno = $conn->query($sql_productos_tecno);



$sql_banners_principales="SELECT * FROM Banners where ubicacion='contenedor_principal' and activo=1";
$result_banner_p=$conn->query($sql_banners_principales);

$sql_banners_secundarios="SELECT * FROM Banners where ubicacion='contenedor_secundario' and activo=1";
$result_banner_s=$conn->query($sql_banners_secundarios);

$sql_banners_extras="SELECT * FROM Banners where ubicacion='contenedor_extra' and activo=1";
$result_banner_ex=$conn->query($sql_banners_extras);



$_productos_oferta = "SELECT 
    o.producto_id,
    p.imagen,
    p.nombre, 
    p.precio AS precio_original,
    o.porcentaje,
    (p.precio - (p.precio * o.porcentaje / 100)) AS precio_oferta,
    COALESCE(m.nombre, 'Sin definir') AS marca_nombre
FROM productos p 
INNER JOIN ofertas o 
    ON p.id = o.producto_id
LEFT JOIN marcas m ON p.marca_id = m.id
    where o.activo=1";

$_result_pro_ofert = $conn->query($_productos_oferta);
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos</title>
    <link rel="stylesheet" href="/PaginaPrincipal/VentanaProductos.css">
    <link rel="stylesheet" href="../carrito/VerCarrito.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Krona+One&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=AR+One+Sans:wght@400;500;700&display=swap" rel="stylesheet">
     <link rel="stylesheet" href="../includes/header.css">


    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap ICONS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

</head>

<body>

    <?php include(__DIR__ . '/../includes/header.php'); ?>

    <main>

        <div id="Box_Principal_Anuncios">

            <?php if ($result_anuncios->num_rows > 0): ?>
                <?php while ($row = $result_anuncios->fetch_assoc()): ?>
                    <div class="anuncio">
                        <img src="../imagenes/<?= $row['imagen'] ?>" alt="Anuncio">
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No hay anuncios activos</p>
            <?php endif; ?>

        </div>

        <div id="carouselExampleIndicators" class="carousel slide " data-bs-ride="carousel">

            <div class="carousel-indicators">
                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active"></button>
                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1"></button>
                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2"></button>
            </div>

            <div class="carousel-inner">

                <?php
                $activeClass = "active";
                if ($result_promociones->num_rows > 0):
                    while ($row_promociones = $result_promociones->fetch_assoc()):
                ?>
                        <div class="carousel-item <?= $activeClass ?>">
                            <img src="../imagenes/<?= $row_promociones['imagen'] ?>" class="d-block w-100" alt="Promoción">
                        </div>
                    <?php
                        $activeClass = ""; // Solo la primera debe tener la clase active
                    endwhile;
                else:
                    ?>
                    <p>No hay promociones activas</p>
                <?php endif; ?>
            </div>

            <!-- BOTÓN PREVIOUS -->
            <button class="carousel-control-prev" type="button"
                data-bs-target="#carouselExampleIndicators"
                data-bs-slide="prev">
                <span class="carousel-control-prev-icon"></span>
                <span class="visually-hidden">Previous</span>
            </button>

            <!-- BOTÓN NEXT -->
            <button class="carousel-control-next" type="button"
                data-bs-target="#carouselExampleIndicators"
                data-bs-slide="next">
                <span class="carousel-control-next-icon"></span>
                <span class="visually-hidden">Next</span>
            </button>

        </div>
        
        <div id="box_principal_productos">

                <div id="titulo_productos">
                    <h3>Productos destacados</h3>
                </div>
            
                <!-- CARRUSEL -->
                <div id="carouselProductos" class="carousel slide" data-bs-ride="carousel">
            
                    <div class="carousel-inner">
            
                        <?php
                        $contador = 0;
                        $total = $result_productos->num_rows;
            
                        while ($prod = $result_productos->fetch_assoc()) {
            
                            // Abrir slide cada 5 productos
                            if ($contador % 6 == 0) {
                                $active = ($contador == 0) ? "active" : "";
                                echo "<div class='carousel-item $active'><div id='Contenedor_productos'>";
                            }
                        ?>
            
                           
                                <div class="tarjeta_producto">
            
                                    <a href="../DetallesProducto/DetallesProducto.php?id=<?= $prod['id'] ?>&categoria_id=<?= $prod['categoria_id'] ?>" class="producto-link">
            
                                        <div class="imagen_producto">
                                            <img src="../imagenes/<?= $prod['imagen'] ?>" alt="<?= $prod['nombre'] ?>">
                                        </div>
            
                                        <span class="marca-producto"><?= htmlspecialchars($prod['marca_nombre']) ?></span>
                                        <h2><?= $prod['nombre'] ?></h2>
                                        
                                        <div class="precios-producto">
                                            
                  
                                            <?php if ($prod['precio'] > $prod['precio_oferta']): ?>
                                              <div class="container_precio_des">
                                                <span class="precio-original">S/ <?= number_format($prod['precio'], 2) ?> </span>
                                                <span class="number_descuento">-<?= (($prod['precio']- $prod['precio_oferta'])/$prod['precio'])*100 ?> %</span>
                                              </div>

                                            <?php endif; ?>
                                            <span class="precio-actual">S/ <?= number_format($prod['precio_oferta'], 2) ?> </span>
                                            
                                            
                                        </div>

                                    </a>
                                    <div class="container_buttons">
                                        <button class="btn-agregar-carrito" data-id="<?= $prod['id'] ?>">
                                            Agregar
                                        </button>  
                                    
                                        <img src="../iconos/corazon.png">
                                        
                                    </div>

            
                                </div>
                            
            
                        <?php
                            $contador++;
            
                            // Cerrar slide cada 5 productos o al final
                            if ($contador % 6 == 0 || $contador == $total) {
                                echo "</div></div>";
                            }
                        }
                        ?>
            
                    </div>
            
                    <!-- BOTÓN ANTERIOR -->
                    <button class="carousel-control-prev" type="button"
                        data-bs-target="#carouselProductos"
                        data-bs-slide="prev">
            
                        <span class="carousel-control-prev-icon"></span>
            
                    </button>
            
                    <!-- BOTÓN SIGUIENTE -->
                    <button class="carousel-control-next" type="button"
                        data-bs-target="#carouselProductos"
                        data-bs-slide="next">
            
                        <span class="carousel-control-next-icon"></span>
            
                    </button>
            
                </div>

        </div>
        
        <?php $contador = 1; ?>
        
        <div id="box_banners_principales">
        
            <?php while ($banner = $result_banner_p->fetch_assoc()): ?>
        
    
                <?php if ($contador == 1): ?>
                    <div class="grupo-2">
                <?php endif; ?>
        
           
                <div class="<?= ($contador == 3) ? 'large_container' : 'container_banner' ?>">
        
                    <a href="<?= str_contains($banner['redireccion'], 'Producto.php?id=') 
                        ? '../DetallesProducto/Detalles' . $banner['redireccion'] 
                        : '../ResultadoProductos/' . $banner['redireccion'] ?>" 
                       class="producto-link">
        
                        <div class="container_imagen_banner">
                            <img src="../imagenes/<?= $banner['imagen'] ?>" alt="<?= $banner['nombre'] ?>">
                        </div>
        
                    </a>
        
                </div>
        
               
                <?php if ($contador == 2): ?>
                    </div>
                <?php endif; ?>
        
                <?php $contador++; ?>
        
            <?php endwhile; ?>
        
        </div>
        
        




        <div id="Box_Principal_Categorias">
            <div id="Contenedor_Categorias">
                <?php while ($cat = $result_categorias->fetch_assoc()): ?>
                    <div class="tarjeta_categoria">
                        <div id="imagen_and_text">
                            <img src="../imagenes/<?= $cat['imagen'] ?>" alt="<?= $cat['nombre'] ?>">
                            <h4><?= $cat['nombre'] ?></h4>
                        </div>

                    </div>
                <?php endwhile; ?>
            </div>

        </div>
        
        <div id="box_banners_secundarios">
            <?php while ($banner = $result_banner_s->fetch_assoc()): ?>
                <div class="large_container">
                    
                    <a href="<?= str_contains($banner['redireccion'], 'Producto.php?id=') 
                        ? '../DetallesProducto/Detalles' . $banner['redireccion'] 
                        : '../ResultadoProductos/' . $banner['redireccion'] ?>" 
                       class="producto-link">
        
                        <div class="container_imagen_banner">
                            <img src="../imagenes/<?= $banner['imagen'] ?>" alt="<?= $banner['nombre'] ?>">
                        </div>
        
                    </a>
                    
                </div>
            
            
            <?php endwhile ?>
        </div>


        <div id="Box_Principal_Ofertas">

            <div id="titulo_ofertas">
                <h3>🔥 Ofertas Especiales</h3>
                <p>Aprovecha los mejores descuentos por tiempo limitado</p>
            </div>

            <div id="Contenedor_Ofertas">
                <?php
                    $_result_pro_ofert->data_seek(0);
                ?>
                    <div class="tarjeta_oferta">
                        <!-- Imagen banner de la oferta -->
                        <div style="position:relative; overflow:hidden;">
                            <span class="oferta_banner_label">OFERTA</span>
                            <img src="../imagenes/imagen oferta.jpg" alt="Oferta" style="width:100%;height:100%;min-height:280px;object-fit:cover;display:block;">
                        </div>

                        <!-- Grid de productos en oferta -->
                        <div class="oferta_productos_grid">
                            <?php while ($proOfert = $_result_pro_ofert->fetch_assoc()): ?>
                                <div class="tarjeta_producto_oferta">
                                    <span class="badge_descuento">-<?= $proOfert['porcentaje'] ?>%</span>
                                    <img src="../imagenes/<?= $proOfert['imagen'] ?>" alt="<?= $proOfert['nombre'] ?>">
                                    <span class="marca-producto"><?= htmlspecialchars($proOfert['marca_nombre']) ?></span>
                                    <p class="nombre_oferta"><?= $proOfert['nombre'] ?></p>
                                    <div class="precios-producto">
                                        <span class="precio-original">S/ <?= number_format($proOfert['precio_original'], 2) ?> un</span>
                                        <span class="precio-actual">S/ <?= number_format($proOfert['precio_oferta'], 2) ?> un</span>
                                    </div>
                                    <div class="container_buttons">
                                        
                                        <button class="btn-agregar-carrito" data-id="<?= $proOfert['producto_id'] ?>">
                                            Agregar
                                        </button>  
                                    
                                        <img src="../iconos/corazon.png">
                                        
                                    </div>
                                    
                                </div>
                            <?php endwhile; ?>
                        </div>
                    </div>
            
            </div>

        </div>

        <div id="contenedor_tecnologia">
            <h3>Productos de Tecnología</h3>
            <div id="productos_tecnologia">
                <?php while ($prod_tecno = $result_productos_tecno->fetch_assoc()): ?>

                    <div class="tarjeta_producto_tecno">

                        <a href="../DetallesProducto/DetallesProducto.php?id=<?= $prod_tecno['id'] ?>" class="producto-link">

                            <div class="imagen_producto_tecno">
                                <img src="../imagenes/<?= $prod_tecno['imagen'] ?>" alt="<?= $prod_tecno['nombre'] ?>">
                            </div>

                            <span class="marca-producto"><?= htmlspecialchars($prod_tecno['marca_nombre']) ?></span>
                            <h4><?= $prod_tecno['nombre'] ?></h4>
                            <div class="precios-producto">
                                <?php if ($prod_tecno['precio'] > $prod_tecno['precio_oferta']): ?>
                                   <div  class="container_precio_des">
                                        <span class="precio-original">S/ <?= number_format($prod_tecno['precio'], 2) ?> un</span>
                                        <span class="number_descuento">-<?= (($prod_tecno['precio']- $prod_tecno['precio_oferta'])/$prod_tecno['precio'])*100 ?> %</span>   
                                   </div>

                                <?php endif; ?>
                                <span class="precio-actual">S/ <?= number_format($prod_tecno['precio_oferta'], 2) ?> un</span>
                            </div>

                        </a>
                        <button class="btn-agregar-carrito" data-id="<?= $prod_tecno['id'] ?>">Agregar</button>

                    </div>

                <?php endwhile; ?>



            </div>
        </div>
        
        <div id="box_banners_extra">
            <?php while ($banner = $result_banner_ex->fetch_assoc()): ?>
                <div class="large_container">
                    
                    <a href="<?= str_contains($banner['redireccion'], 'Producto.php?id=') 
                        ? '../DetallesProducto/Detalles' . $banner['redireccion'] 
                        : '../ResultadoProductos/' . $banner['redireccion'] ?>" 
                       class="producto-link">
        
                        <div class="container_imagen_banner">
                            <img src="../imagenes/<?= $banner['imagen'] ?>" alt="<?= $banner['nombre'] ?>">
                        </div>
        
                    </a>
                    
                </div>
            
            
            <?php endwhile ?>
        </div>
        
        




        <div id="box_principal_finalOption">
            <div id="title">
                <p>Conoce mas de Jeap</p>
            </div>

            <div id="container_opciones">


                <div class="box_option">
                    <img src="../iconos/icono pregunta.png" alt="">
                    <p>Preguntas frecuentes</p>
                </div>

                <div class="box_option">
                    <img src="../iconos/icono de tienda.png" alt="">
                    <p>Nuestras tiendas</p>
                </div>

                <div class="box_option">
                    <img src="../iconos/icono libro.png" alt="">
                    <p>Libro de reclamaciones</p>
                </div>


            </div>
        </div>


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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../Javascript/carrito.js"></script>
</body>

</html>