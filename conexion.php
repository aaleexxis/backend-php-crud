<?php

$envPath = __DIR__ . "/.env";

if (file_exists($envPath)) {
    $env = parse_ini_file($envPath);
} else {
    $env = [];
}

$host = $env["DB_HOST"] ?? "localhost";
$baseDatos = $env["DB_NAME"] ?? "backend_php";
$usuarioBD = $env["DB_USER"] ?? "root";
$contrasenaBD = $env["DB_PASS"] ?? "";

$dsn = "mysql:host=$host;dbname=$baseDatos;charset=utf8mb4";

$opciones = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false
];

try {
    $pdo = new PDO(
        $dsn,
        $usuarioBD,
        $contrasenaBD,
        $opciones
    );
} catch (PDOException $error) {
    exit("Error de conexión con la base de datos.");
}