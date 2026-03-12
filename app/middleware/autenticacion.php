<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function requerirLogin()
{
    if (empty($_SESSION['usuario_id'])) {
        header("Location: /marketing/public/login");
        exit;
    }
}

function esAdmin(): bool
{
    if (isset($_SESSION['usuario_rol_id']) && (int)$_SESSION['usuario_rol_id'] === 1) {
        return true;
    }

    if (isset($_SESSION['usuario']['rol_id']) && (int)$_SESSION['usuario']['rol_id'] === 1) {
        return true;
    }

    if (isset($_SESSION['usuario_rol']) && $_SESSION['usuario_rol'] === 'admin') {
        return true;
    }

    return false;
}

function requerirAdmin()
{
    requerirLogin();

    if (!esAdmin()) {
        http_response_code(403);
        echo "<h3 style='color:red;text-align:center;margin-top:40px;'>🚫 Acceso denegado</h3>";
        echo "<p style='text-align:center;'>Esta sección es solo para administradores.</p>";
        exit;
    }
}
