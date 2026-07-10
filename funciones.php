<?php

function e($valor): string {
    return htmlspecialchars($valor ?? "", ENT_QUOTES, "UTF-8");
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

function requerirLogin(): void {
    if (!estaLogueado()) {
        redirigir("login.php");
    }
}

function requerirAdministrador(): void {
    requerirLogin();

    if (!esAdministrador()) {
        http_response_code(403);
        exit("No tienes permiso para acceder a esta página.");
    }
}

function generarTokenCsrf(): string {
    if (empty($_SESSION["csrf_token"])) {
        $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
    }

    return $_SESSION["csrf_token"];
}

function verificarTokenCsrf(string $token): bool {
    return isset($_SESSION["csrf_token"]) &&
        hash_equals($_SESSION["csrf_token"], $token);
}