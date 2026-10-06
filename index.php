<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

try {
    if (session_status() !== PHP_SESSION_ACTIVE) { session_start(); } // Asegura que la sesión esté iniciada
    require_once __DIR__ . '/app/index.php';
    require_once __DIR__ . '/app/core/routes.php';
} catch (Throwable $e) {
    http_response_code(500);
    echo "<h1>Error en la aplicación</h1><pre>";
    echo "<strong>Mensaje:</strong> " . htmlspecialchars($e->getMessage()) . "\n\n";
    echo "<strong>Archivo:</strong> " . $e->getFile() . " (Línea: " . $e->getLine() . ")\n\n";
    echo "<strong>Stack Trace:</strong>\n" . htmlspecialchars($e->getTraceAsString());
    echo "</pre>";
}
?>