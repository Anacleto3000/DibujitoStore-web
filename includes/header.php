<div id="overlay"></div>

<header>

    <nav id="buttons_header">
    <img id="logo" src="../iconos/image 2.png" alt="">
        
     <div id="elementos_derecha">
         
             <div id="container_button_menu">
                 <button>Categorias</button>
             </div>
             
            <div id="Barra_busqueda">

                <form action="/ResultadoProductos/ResultadoProductos.php" method="GET">
                    <input type="text" name="busqueda" placeholder="Buscar productos...">

                    <button id="Button_buscar" type="submit">
                        <img src="../iconos/image 21.png" alt="">
                    </button>
                </form>

            </div>
     </div>

        <div id="Box_Button_Otros">

            <?php
            if (isset($_SESSION["usuario_id"])) {
            ?>
                <a href="../PerfilCliente.php" style="text-decoration: none;"><span style="background-color: white;color:black;padding: 10px;border-radius: 10px;">Hola &nbsp;<?php echo $_SESSION["usuario_name"] ?></span></a>
                <a href="../account/LogoutCliente.php" style="text-decoration: none;"><span>Cerrar Sesión</span></a>

                <div id="Box_Button_carrito">
                    <img src="../iconos/image 20.png" alt="">
                    <span id="btn-Carrito">Carrito</span>
                </div>

            <?php
            } else {
            ?>  
                <div id="container_button_login">
                <i class="bi bi-person-fill" style="font-size:35px;"></i>
                <a href="../account/LoginCliente.php"><span id="button_iniciar_sesion">Iniciar sesión</span></a>          
                </div>

            <?php
            }
            ?>

        </div>

        
    </nav>
    
       <div class="container_header_opciones">
           
           <div id="container_ubicacion">Ubicacion</div>
           
           <div id="navegation_items">
               <span>Nuevos productos</span>
               <span>Destacados</span>
               <span>Para ti</span>
               <span>Seleccion especial</span>
           </div>

       </div>
    
</header>
