<?php

function e(?string $texto): string {
    return htmlspecialchars($texto ?? "", ENT_QUOTES, "UTF-8");
}

function estaLogueado(): bool {
    return isset($_SESSION["usuario_id"]);
}

function esAdministrador(): bool {
    return ($_SESSION["rol"] ?? "") === "Administrador";
}

function redirigir(string $ruta): void {
    header("Location: $ruta");
    exit;
}