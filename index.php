<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

$controller = isset($_GET['controller']) ? $_GET['controller'] : null;
$action     = isset($_GET['action'])     ? $_GET['action']     : null;
$view       = isset($_GET['view'])       ? $_GET['view']       : null;

if ($view == 'productos') {
    include('views/productos/index.php');
    exit;
}

if ($view == 'grupos') {
    include('views/grupos/index.php');
    exit;
}

if ($view == 'asignacion') {
    include('views/asignacion/index.php');
    exit;
}

if (!$controller && !$action) {
    include('views/index.php');
    exit;
}

$controllerName = $controller . "Controller";
$controllerFile = "controllers/" . $controllerName . ".php";

if (file_exists($controllerFile)) {
    require_once $controllerFile;

    if (class_exists($controllerName)) {
        $objController = new $controllerName();

        if (method_exists($objController, $action)) {
            $objController->$action();
        } else {
            echo "La acción <strong>$action</strong> no existe en el controlador <strong>$controllerName</strong>.";
        }
    } else {
        echo "El controlador <strong>$controllerName</strong> no existe.";
    }
} else {
    echo "No se encontró el archivo <strong>$controllerFile</strong>.";
}
