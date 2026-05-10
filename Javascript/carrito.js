console.log("JS cargado correctamente");


// Obtener la ruta base del proyecto a partir de la ubicación de este script
const carritoScriptSrc = document.currentScript ? document.currentScript.src : document.querySelector('script[src*="carrito.js"]').src;
const baseUrl = carritoScriptSrc.replace(/(?:\/JavaScript|\/Javascript|\/javascript)\/carrito\.js$/i, '');
const carritoPath = baseUrl + '/carrito/';

document.addEventListener("DOMContentLoaded", () => {
    const boton = document.getElementById("btn-Carrito");

    if (boton) {
        boton.addEventListener("click", crearCarritoFlotante);

    }

});



function crearCarritoFlotante() {
    document.getElementById("overlay").style.display = "flex";
    let carrito = document.getElementById("carrito");

    if (!carrito) {
        carrito = document.createElement("div");
        carrito.id = "carrito";
        carrito.classList.add("carrito-flotante");

        carrito.innerHTML = `
    <div class="Container_ordenar1">
      <div id=container_superior>

            <h3 id="title_carrito">🛒 Mi carrito</h3>

           <div id="contenedor_boton_cerrar">
                 <button id="cerrar-carrito">Cerrar</button>
           </div>

       </div>
       
       <div id="contenedor-productos-carrito">

         // Los productos se cargarán aquí dinámicamente
         
       </div>

    </div>

      <div class="Container_ordenar2">
           <div id="total-carrito">
             <span>Total: </span>
             <span id="valor-total-carrito">S/ 0.00</span>
          </div>

          <div id="contenedor_boton_pagar">
                 <button id="boton-pagar">Ir a pagar</button>
           </div>

      </div>

      
    `;

        document.body.appendChild(carrito);

        btnCerrarCarrito = document.getElementById("cerrar-carrito")
        btnCerrarCarrito.addEventListener("click", ocultarCarrito);


        document.getElementById("boton-pagar").addEventListener("click", () => {
            window.location.href = "../VentanaPagar/Pagar.php";
        });

    }


    carrito.classList.add("activo");

    fetch(carritoPath + 'VerCarrito.php')
        .then(res => res.text())
        .then(data => {
            console.log(data);
            document.getElementById("contenedor-productos-carrito").innerHTML = data;
            calcularTotalCarrito();

        });

}



function ocultarCarrito() {
    document.getElementById("carrito").classList.remove("activo");
    document.getElementById("overlay").style.display = "none";
}



document.querySelectorAll('.btn-agregar-carrito').forEach(boton => {
    boton.addEventListener('click', () => {
        const productoId = boton.dataset.id;

        fetch(carritoPath + 'AgregarCarrito.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: 'producto_id=' + productoId
        })
            .then(res => res.text())
            .then(respuesta => {
                alert(respuesta);
            });
    });
});

document.addEventListener("click", function (e) {
    if (e.target.classList.contains("btn-eliminar-producto")) {
        const productoId = e.target.dataset.id;

        fetch(carritoPath + 'EliminarDelCarrito.php', {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body: "producto_id=" + productoId
        })
            .then(res => res.text())
            .then(respuesta => {
                alert(respuesta);
                crearCarritoFlotante(); // recarga el carrito
            });
    }
});


document.addEventListener("input", function (e) {
    if (e.target.classList.contains("cantidad-producto")) {
        const productoId = e.target.dataset.id;
        const cantidad = e.target.value;


        // Validación básica
        if (cantidad < 1) return;

        fetch(carritoPath + 'ActualizarDelCarrito.php', {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body: `producto_id=${productoId}&cantidad=${cantidad}`
        })
            .then(res => res.text())
            .then(respuesta => {
                console.log(respuesta);
                crearCarritoFlotante();




            });
    }


});

function calcularTotalCarrito() {
    const totalSpan = document.getElementById("valor-total-carrito");
    console.log("Span total:", totalSpan);

    const productos = document.querySelectorAll(".item-carrito");

    let total = 0;

    productos.forEach(producto => {
        const precio = Number(
            producto.querySelector(".precio-producto").dataset.precio
        );
        const cantidad = Number(
            producto.querySelector(".cantidad-producto").value);

        total += precio * cantidad;
    });

    totalSpan.textContent = "S/ " + total.toFixed(2);
}






