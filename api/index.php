<?php
/**
 * API REST - Punto de Entrada
 * 
 * Inicializa Slim Framework y carga las rutas de la API
 */

require __DIR__ . '/vendor/autoload.php';

use Slim\Factory\AppFactory;
use Slim\Middleware\ErrorMiddleware;

// Crear la aplicación Slim
$app = AppFactory::create();

// Agregar middleware para manejar errores
$errorMiddleware = $app->addErrorMiddleware(true, true, true);

// CORS Middleware
$app->add(function ($request, $handler) {
    $response = $handler->handle($request);
    return $response
        ->withHeader('Access-Control-Allow-Origin', '*')
        ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
        ->withHeader('Access-Control-Allow-Headers', 'Content-Type, Authorization')
        ->withHeader('Content-Type', 'application/json');
});

// Manejar OPTIONS requests
$app->options('/{routes:.+}', function ($request, $handler) {
    $response = $handler->handle($request);
    return $response;
});

// Cargar las rutas
require __DIR__ . '/src/routes.php';

// Ejecutar la aplicación
$app->run();
?>
