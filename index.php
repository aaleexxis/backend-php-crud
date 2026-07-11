<?php

session_start();

require_once __DIR__ . "/funciones.php";

if (estaLogueado()) {
    header("Location: panel.php");
    exit;
}

header("Location: login.php");
exit;