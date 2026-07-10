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