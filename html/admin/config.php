<?php
// Configuración de credenciales de admin
define('ADMIN_USER', 'admin');
define('ADMIN_PASS', 'Cleaning2025!'); // Cambia esta contraseña después

// Iniciar sesión
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Función para verificar si está logueado
function isLoggedIn() {
    return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
}

// Función para requerir login
function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: login.php');
        exit;
    }
}
?>
