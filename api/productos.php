<?php

require_once __DIR__ . "/../conexion.php";

header("Content-Type: application/json; charset=utf-8");

try {
    $busqueda = trim($_GET["busqueda"] ?? "");

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