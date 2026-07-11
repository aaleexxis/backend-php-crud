# Backend PHP CRUD - Gestión de usuarios y productos

Proyecto backend desarrollado con **PHP, MySQL y PDO** como práctica de desarrollo web orientada a un perfil **junior backend**.

Este proyecto ha sido creado por un estudiante de **Ingeniería Informática** con el objetivo de consolidar conocimientos fundamentales de programación web, bases de datos, autenticación, roles, seguridad básica y operaciones CRUD.

## Descripción del proyecto

La aplicación permite gestionar usuarios y productos mediante un sistema de autenticación con roles.

Incluye un panel privado donde los usuarios pueden iniciar sesión y, en el caso del administrador, acceder a un dashboard con estadísticas y a la gestión completa de usuarios y productos.

El proyecto está desarrollado sin frameworks, utilizando PHP puro, para comprender mejor cómo funcionan internamente conceptos como sesiones, conexión a base de datos, consultas preparadas, validación de formularios y protección de rutas.

## Tecnologías utilizadas

* PHP 8
* MySQL / MariaDB
* PDO
* HTML
* Git
* GitHub
* XAMPP
* phpMyAdmin

## Funcionalidades principales

### Autenticación y usuarios

* Registro de usuarios.
* Inicio de sesión.
* Cierre de sesión.
* Contraseñas cifradas con `password_hash()`.
* Verificación de contraseñas con `password_verify()`.
* Gestión de sesiones.
* Roles de usuario:

  * Administrador
  * Usuario
* Protección de páginas privadas.
* Protección de rutas por rol.

### Panel de administración

* Dashboard privado para administradores.
* Total de usuarios registrados.
* Total de productos.
* Productos agotados.
* Valor total del inventario.

### CRUD de usuarios

* Listado de usuarios.
* Edición de roles.
* Eliminación de usuarios.
* Restricción para evitar que un administrador se elimine a sí mismo.

### CRUD de productos

* Listado de productos.
* Creación de productos.
* Edición de productos.
* Eliminación de productos.
* Buscador por nombre.
* Filtro por stock:

  * Todos
  * Disponibles
  * Agotados
* Ordenación por:

  * Más recientes
  * Nombre A-Z
  * Precio menor a mayor
  * Precio mayor a menor
  * Más stock
* Paginación de resultados.

### Seguridad implementada

* Consultas preparadas con PDO.
* Escape de salida con `htmlspecialchars()`.
* Protección contra XSS básica.
* Protección CSRF en formularios críticos.
* Regeneración de ID de sesión tras iniciar sesión.
* Validación de formularios.
* Control de acceso por sesión.
* Control de acceso por rol.

## Estructura del proyecto

```text
backend-php/
│
├── includes/
│   ├── header.php
│   ├── nav.php
│   └── footer.php
│
├── conexion.php
├── funciones.php
├── index.php
├── login.php
├── logout.php
├── registro.php
├── panel.php
│
├── usuarios.php
├── editar_usuario.php
├── eliminar_usuario.php
│
├── productos.php
├── crear_producto.php
├── editar_producto.php
├── eliminar_producto.php
│
├── database.sql
├── .gitignore
└── README.md
```

## Instalación del proyecto

1. Clonar o descargar el repositorio.

```bash
git clone https://github.com/aaleexxis/backend-php-crud.git
```

2. Copiar la carpeta del proyecto dentro de:

```text
C:\xampp\htdocs\backend-php
```

3. Encender **Apache** y **MySQL** desde XAMPP.

4. Abrir phpMyAdmin:

```text
http://localhost/phpmyadmin
```

5. Importar el archivo:

```text
database.sql
```

6. Revisar la configuración de conexión en `conexion.php`:

```php
$host = "localhost";
$baseDatos = "backend_php";
$usuarioBD = "root";
$contrasenaBD = "";
```

7. Abrir el proyecto en el navegador:

```text
http://localhost/backend-php/
```

## Usuario de prueba

Administrador:

```text
Usuario: admin
Contraseña: admin123
```

## Capturas de pantalla

### Login

Añadir aquí captura de la pantalla de inicio de sesión.

### Panel privado

Añadir aquí captura del dashboard de administración.

### Gestión de productos

Añadir aquí captura del listado de productos con búsqueda, filtros, ordenación y paginación.

### Gestión de usuarios

Añadir aquí captura del listado de usuarios.

## Aprendizajes principales

Durante el desarrollo de este proyecto se han trabajado los siguientes conceptos:

* Estructura básica de un proyecto PHP.
* Separación de código reutilizable mediante `includes`.
* Conexión a base de datos con PDO.
* Uso de consultas preparadas.
* Gestión de sesiones.
* Registro e inicio de sesión.
* Cifrado de contraseñas.
* Roles de usuario.
* Protección de rutas privadas.
* CRUD completo sobre varias entidades.
* Validación de formularios.
* Protección CSRF.
* Escape de datos para prevenir XSS.
* Búsqueda, filtros, ordenación y paginación.
* Control de versiones con Git.
* Publicación del proyecto en GitHub.

## Objetivo personal

Este proyecto forma parte de mi preparación como estudiante de **Ingeniería Informática** para adquirir una base sólida en desarrollo backend y poder optar a posiciones de **programador junior**, especialmente en entornos relacionados con PHP, MySQL y desarrollo web.

## Estado del proyecto

Proyecto en desarrollo y mejora continua.

Próximas posibles mejoras:

* Añadir estilos con CSS.
* Crear un diseño responsive.
* Separar configuración sensible en un archivo `.env`.
* Añadir subida de imágenes para productos.
* Añadir tests básicos.
* Migrar el proyecto progresivamente a Laravel.
