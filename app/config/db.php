<?php
/*
 * db.php
 * Archivo de conexión usando PDO.
 * Se conecta usando las credenciales de config.php
 */

$config = require __DIR__ . '/config.php';

try {
    $dsn = "mysql:host={$config['db']['host']};dbname={$config['db']['name']};charset={$config['db']['charset']}";
    
    $options = [
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
      PDO::ATTR_EMULATE_PREPARES => false, // Recomendado false para seguridad nativa
    ];

    $pdo = new PDO($dsn, $config['db']['user'], $config['db']['pass'], $options);

} catch (PDOException $e) {
    // En producción, es mejor redirigir a una página de error genérica 
    // o registrar el error en un log, nunca mostrar $e->getMessage() al usuario final.
    error_log($e->getMessage()); // Guarda el error en el log del servidor
    die("Error de conexión a la base de datos. Por favor intenta más tarde.");
}