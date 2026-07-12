<?php

require_once __DIR__ . "/funciones.php";

$titulo = "Prueba de API";

require_once __DIR__ . "/includes/header.php";

?>

<h1>Prueba de API REST</h1>

<p>
    Esta página permite probar visualmente la API de productos del proyecto.
</p>

<hr>

<h2>Obtener todos los productos</h2>

<button type="button" onclick="obtenerTodos()">
    Cargar productos
</button>

<hr>

<h2>Buscar productos por nombre</h2>

<input
    type="text"
    id="busqueda"
    placeholder="Ejemplo: teclado"
>

<button type="button" onclick="buscarPorNombre()">
    Buscar
</button>

<hr>

<h2>Buscar producto por ID</h2>

<input
    type="number"
    id="productoId"
    placeholder="Ejemplo: 1"
    min="1"
>

<button type="button" onclick="buscarPorId()">
    Buscar por ID
</button>

<hr>

<h2>Respuesta de la API</h2>

<pre id="resultado">Aquí aparecerá la respuesta JSON...</pre>

<script>
    const resultado = document.getElementById("resultado");

    async function consultarApi(url) {
        try {
            resultado.textContent = "Cargando...";

            const respuesta = await fetch(url);
            const datos = await respuesta.json();

            resultado.textContent = JSON.stringify(datos, null, 4);
        } catch (error) {
            resultado.textContent = "Error al consultar la API.";
        }
    }

    function obtenerTodos() {
        consultarApi("/backend-php/api/productos.php");
    }

    function buscarPorNombre() {
        const busqueda = document.getElementById("busqueda").value.trim();

        if (busqueda === "") {
            resultado.textContent = "Escribe un nombre para buscar.";
            return;
        }

        consultarApi(
            "/backend-php/api/productos.php?busqueda=" + encodeURIComponent(busqueda)
        );
    }

    function buscarPorId() {
        const id = document.getElementById("productoId").value.trim();

        if (id === "") {
            resultado.textContent = "Escribe un ID de producto.";
            return;
        }

        consultarApi(
            "/backend-php/api/productos.php?id=" + encodeURIComponent(id)
        );
    }
</script>

<?php require_once __DIR__ . "/includes/footer.php"; ?>