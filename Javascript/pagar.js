        // Actualizar badge con número de productos
        const productos = document.querySelectorAll('.container_producto');
        const cartBadge = document.getElementById('cart-count');
        cartBadge.textContent = productos.length + (productos.length === 1 ? ' producto' : ' productos');

        // Animación de entrada en cada producto
        productos.forEach((el, i) => {
            el.style.animationDelay = (i * 0.08) + 's';
        });

        // Hover en botón continuar
        const btnContinuar = document.getElementById('btn-continuar');
        if (btnContinuar) {
            btnContinuar.addEventListener('click', function() {
                this.textContent = 'Procesando...';
                this.disabled = true;
                setTimeout(() => {
                    this.innerHTML = 'Continuar con el pago <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>';
                    this.disabled = false;
                }, 1500);
            });
        }