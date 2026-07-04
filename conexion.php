<?php

$host = "localhost";
$baseDatos = "backend_php";
$usuarioBD = "root";
$contrasenaBD = "";

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
    exit("Error de conexión: " . $error->getMessage());
}