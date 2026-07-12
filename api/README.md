# API REST - Productos

Esta carpeta contiene los endpoints de la API REST del proyecto.

Actualmente la API permite consultar productos en formato JSON.

## Base URL local

```text
http://localhost/backend-php/api
```

## Endpoints disponibles

### Obtener todos los productos

```http
GET /productos.php
```

Ejemplo:

```text
http://localhost/backend-php/api/productos.php
```

Respuesta esperada:

```json
{
    "estado": "ok",
    "total": 3,
    "productos": [
        {
            "id": 1,
            "nombre": "Teclado mecánico",
            "descripcion": "Teclado gaming con switches mecánicos",
            "precio": "49.99",
            "stock": 10,
            "creado_en": "2026-07-..."
        }
    ]
}
```

### Buscar productos por nombre

```http
GET /productos.php?busqueda=teclado
```

Ejemplo:

```text
http://localhost/backend-php/api/productos.php?busqueda=teclado
```

### Obtener un producto por ID

```http
GET /productos.php?id=1
```

Ejemplo:

```text
http://localhost/backend-php/api/productos.php?id=1
```

Respuesta esperada:

```json
{
    "estado": "ok",
    "producto": {
        "id": 1,
        "nombre": "Teclado mecánico",
        "descripcion": "Teclado gaming con switches mecánicos",
        "precio": "49.99",
        "stock": 10,
        "creado_en": "2026-07-..."
    }
}
```

### Producto no encontrado

Si el producto no existe, la API devuelve un error 404:

```json
{
    "estado": "error",
    "mensaje": "Producto no encontrado."
}
```

### Método no permitido

Esta API solo acepta peticiones `GET`.

Si se usa otro método, devuelve un error 405:

```json
{
    "estado": "error",
    "mensaje": "Método no permitido. Usa GET."
}
```

## Tecnologías usadas

* PHP
* MySQL
* PDO
* JSON
* Consultas preparadas