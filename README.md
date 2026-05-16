

# Tienda Dibujito 🎨

¡Bienvenido al repositorio de **Tienda Dibujito**! Este es un proyecto de comercio electrónico (E-commerce) desarrollado principalmente en PHP y MySQL, diseñado para ofrecer una experiencia de compra fluida y visualmente atractiva para los usuarios.

#Descripcion 
Este es mi primer proyecto iniciado el año 2025 del mes de diciembre

## 🚀 Características Principales

- **Catálogo de Productos**: Visualización de productos en la página principal con imágenes, descripciones y precios (incluyendo precios con descuento).
- **Detalles de Producto**: Páginas dedicadas para ver información extendida de cada producto.
- **Carrito de Compras**: Sistema para añadir, visualizar y gestionar los productos seleccionados antes de la compra.
- **Proceso de Pago (Checkout)**: Flujo de pago y finalización de compra integrado.
- **Búsqueda de Productos**: Barra de búsqueda funcional para encontrar artículos específicos rápidamente.
- **Gestión de Cuentas**: Sistema de registro e inicio de sesión para usuarios.


## 🛠️ Tecnologías Utilizadas

- **Backend**: PHP 
- **Base de Datos**: MySQL
- **Frontend**: HTML5, CSS3 (Vanilla), JavaScript
- **Arquitectura**: Basada en componentes modulares (ej. `includes/header.php`)

## 📁 Estructura del Proyecto

El proyecto está organizado en las siguientes carpetas principales:

- `/PaginaPrincipal` - Contiene la vista principal y listado de productos (`VentanaProductos.php`).
- `/DetallesProducto` - Lógica y vistas para la página individual de cada producto.
- `/carrito` - Funcionalidad del carrito de compras.
- `/VentanaPagar` - Vistas y lógica para el proceso de pago.
- `/account` - Gestión de usuarios (login, registro, perfil).
- `/ResultadoProductos` - Vistas para los resultados de la barra de búsqueda.
- `/Api` - Endpoints para consultas asíncronas.
- `/includes` - Componentes reutilizables como el header y footer.
- `/Javascript` - Scripts de lógica frontend.
- `/iconos` e `/imagenes` - Recursos multimedia y gráficos.
- `config.php` - Archivo de configuración centralizada para la conexión a la base de datos (¡No subir a repositorios públicos con credenciales reales!).
- `u925143271_TiendaDibujito.sql` - Script completo para la creación e inserción de datos iniciales en la base de datos.

## ⚙️ Instalación y Configuración Local

Sigue estos pasos para desplegar el proyecto en tu entorno local:

1. **Clonar el repositorio**:
   ```bash
   git clone <url-del-repositorio>
   ```

2. **Preparar el entorno del Servidor**:
   Asegúrate de tener instalado un servidor web local con soporte para PHP y MySQL (como XAMPP, WAMP, o MAMP). Mueve la carpeta del proyecto a la ruta de publicación de tu servidor (por ejemplo, `htdocs` en XAMPP).

3. **Configurar la Base de Datos**:
   - Abre tu gestor de base de datos preferido (ej. phpMyAdmin).
   - Crea una base de datos nueva (por defecto: `u925143271_TiendaDibujito`).
   - Importa el archivo `u925143271_TiendaDibujito.sql` incluido en la raíz del proyecto para crear las tablas y poblar los datos de prueba.

4. **Configurar las Credenciales**:
   Abre el archivo `config.php` en la raíz del proyecto y ajusta las variables para que coincidan con tus credenciales locales de MySQL:
   ```php
   $host = "localhost";
   $user = "tu_usuario_local"; // Generalmente "root"
   $password = "tu_contraseña"; // Generalmente vacío "" en local
   $db = "u925143271_TiendaDibujito";
   ```

5. **¡Listo!**:
   Abre tu navegador y accede a `http://localhost/PrimerProyecto` (o la ruta donde hayas ubicado el proyecto). La aplicación debería cargar a través de `index.php`.

## 🔒 Consideraciones de Seguridad

- **`.gitignore`**: Asegúrate de que tu archivo `.gitignore` incluya `config.php` para evitar exponer credenciales de producción en GitHub o cualquier otro sistema de control de versiones.
- Las imágenes del proyecto están ignoradas por el control de versiones a excepción de los archivos `.gitkeep` para mantener la estructura de directorios.

---



## Consideraciones adicionales

   Este repositorio tiene relacion con otro llamado DibujitoStore-Admin, asi que si usted desea gestionar la pagina web deberá usar ambos repositorios. Por otro lado el diseño aun no es reponsive al 100%, por lo que no se recomienda su uso en dispositivos moviles.

*Desarrollado para Tienda Dibujito.*
