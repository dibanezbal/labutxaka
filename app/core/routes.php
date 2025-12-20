<?php

// Inicia la sesión si no está ya iniciada
if (session_status() !== PHP_SESSION_ACTIVE) {
  session_start();
}

$c = $_GET['c'] ?? 'usuarios';
$a = $_GET['a'] ?? 'index';

$public = [
  'usuarios' => ['index', 'login', 'signup'],
];

// Redirige al inicio si no está logeado y no es una ruta pública
$isLogged = !empty($_SESSION['user_id']);
$isPublic = isset($public[$c]) && in_array($a, $public[$c], true);

if (!$isLogged && !$isPublic) {
  header('Location: index.php?c=usuarios&a=index');
  exit;
}

if ($isLogged && $c === 'usuarios' && $a === 'index') {
  header('Location: index.php?c=movimientos&a=index');
  exit;
}


// Funciones para cargar controladores y acciones
function loadController($controllerName)
{
    $controllerClass = ucfirst(strtolower($controllerName)) . 'Controller';
    $controllerFile = __DIR__ . '/../controllers/' . $controllerClass . '.php';

    if (!file_exists($controllerFile)) {
        throw new RuntimeException("Archivo de controlador no encontrado: " . $controllerFile);
    }

    require_once $controllerFile;

    if (!class_exists($controllerClass)) {
        throw new RuntimeException("Clase de controlador no encontrada: " . $controllerClass);
    }

    return new $controllerClass();
}

function loadAction($controllerInstance, $action, $id = null)
{
    if (!method_exists($controllerInstance, $action)) {
        throw new RuntimeException("La acción '" . $action . "' no existe en el controlador " . get_class($controllerInstance));
    }

    if ($id !== null) {
        $controllerInstance->$action($id);
    } else {
        $controllerInstance->$action();
    }
}
?>