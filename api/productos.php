<?php

require_once __DIR__ . "/../conexion.php";

header("Content-Type: application/json; charset=utf-8");

if ($_SERVER["REQUEST_METHOD"] !== "GET") {
    http_response_code(405);

    echo json_encode([
        "estado" => "error",
        "mensaje" => "Método no permitido. Usa GET."
    ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

    exit;
}

try {
    $id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);
    $busqueda = trim($_GET["busqueda"] ?? "");

    if ($id !== null && $id !== false) {
        $consulta = $pdo->prepare(
            "SELECT id, nombre, descripcion, precio, stock, creado_en
             FROM productos
             WHERE id = :id
             LIMIT 1"
        );

        $consulta->execute([
            "id" => $id
        ]);

        $producto = $consulta->fetch();

        if (!$producto) {
            http_response_code(404);

            echo json_encode([
                "estado" => "error",
                "mensaje" => "Producto no encontrado."
            ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

            exit;
        }

        echo json_encode([
            "estado" => "ok",
            "producto" => $producto
        ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

        exit;
    }

    $sql = "SELECT id, nombre, descripcion, precio, stock, creado_en
            FROM productos";

    $parametros = [];

    if ($busqueda !== "") {
        $sql .= " WHERE nombre LIKE :busqueda";
        $parametros["busqueda"] = "%" . $busqueda . "%";
    }

    $sql .= " ORDER BY id DESC";

    $consulta = $pdo->prepare($sql);
    $consulta->execute($parametros);

    $productos = $consulta->fetchAll();

    echo json_encode([
        "estado" => "ok",
        "total" => count($productos),
        "productos" => $productos
    ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

} catch (PDOException $error) {
    http_response_code(500);

    echo json_encode([
        "estado" => "error",
        "mensaje" => "Error al obtener los productos."
    ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
}