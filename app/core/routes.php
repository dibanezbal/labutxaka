<?php
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

<?php