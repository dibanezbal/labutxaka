<?php
require_once __DIR__ . "/config/config.php";
require_once __DIR__ . "/core/routes.php";
require_once __DIR__ . "/config/database.php";


// Determina el controlador a cargar
if (isset($_GET['c'])) {
    $controller = loadController($_GET['c']);
    $action = $_GET['a'] ?? MAIN_ACTION; // Si no hay acción, usa la por defecto
} else {
    // Si no hay controlador en la URL, usa el por defecto
    $controller = loadController(DEFAULT_CONTROLLER);
    $action = MAIN_ACTION;
}

// Carga la acción
$id = $_GET['id'] ?? null;
loadAction($controller, $action, $id);
?>