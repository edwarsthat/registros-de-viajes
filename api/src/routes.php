<?php
/**
 * Definición de Rutas de la API REST
 */

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

// Importar el controlador de viajes
require __DIR__ . '/controllers/ViajesController.php';

// ============================================
// RUTAS DE VIAJES
// ============================================

/**
 * GET /api/viajes/por-placa/{placa}
 * 
 * Obtiene todos los viajes de un vehículo por su placa
 * 
 * Respuesta exitosa (200):
 * {
 *   "status": "success",
 *   "message": "Viajes encontrados",
 *   "data": [
 *     {
 *       "idviaje": 1,
 *       "placa": "ABC123",
 *       "color": "Rojo",
 *       "ciudad_origen": "Bogotá",
 *       "ciudad_destino": "Medellín",
 *       "tiempo_horas": 5.5,
 *       "fecha": "2024-11-04"
 *     }
 *   ]
 * }
 * 
 * Respuesta error (404):
 * {
 *   "status": "error",
 *   "message": "No hay viajes para este vehículo"
 * }
 */
$app->get('/api/viajes/por-placa/{placa}', function (Request $request, Response $response, $args) {
    $placa = strtoupper($args['placa']);
    $viajes = ViajesController::obtenerViajesPorPlaca($placa);
    
    $response->getBody()->write(json_encode($viajes));
    return $response;
});

/**
 * GET /api/viajes
 * Obtiene todos los viajes
 */
$app->get('/api/viajes', function (Request $request, Response $response) {
    $viajes = ViajesController::obtenerTodos();
    
    $response->getBody()->write(json_encode($viajes));
    return $response;
});

/**
 * GET /api/viajes/{id}
 * Obtiene un viaje específico por ID
 */
$app->get('/api/viajes/{id}', function (Request $request, Response $response, $args) {
    $viaje = ViajesController::obtenerPorId($args['id']);
    
    $response->getBody()->write(json_encode($viaje));
    return $response;
});

/**
 * POST /api/viajes
 * Crea un nuevo viaje
 */
$app->post('/api/viajes', function (Request $request, Response $response) {
    $data = json_decode($request->getBody(), true);
    $resultado = ViajesController::crear($data);
    
    $response->getBody()->write(json_encode($resultado));
    return $response->withStatus($resultado['status'] === 'success' ? 201 : 400);
});

/**
 * PUT /api/viajes/{id}
 * Actualiza un viaje existente
 */
$app->put('/api/viajes/{id}', function (Request $request, Response $response, $args) {
    $data = json_decode($request->getBody(), true);
    $resultado = ViajesController::actualizar($args['id'], $data);
    
    $response->getBody()->write(json_encode($resultado));
    return $response;
});

/**
 * DELETE /api/viajes/{id}
 * Elimina un viaje
 */
$app->delete('/api/viajes/{id}', function (Request $request, Response $response, $args) {
    $resultado = ViajesController::eliminar($args['id']);
    
    $response->getBody()->write(json_encode($resultado));
    return $response;
});

// ============================================
// RUTA DE PRUEBA
// ============================================

/**
 * GET /
 * Ruta de prueba para verificar que la API funciona
 */
$app->get('/', function (Request $request, Response $response) {
    $data = [
        'status' => 'success',
        'message' => 'API REST funcionando correctamente',
        'version' => '1.0',
        'endpoints' => [
            'GET' => [
                '/api/viajes' => 'Obtener todos los viajes',
                '/api/viajes/{id}' => 'Obtener viaje por ID',
                '/api/viajes/por-placa/{placa}' => 'Obtener viajes por placa del vehículo'
            ],
            'POST' => [
                '/api/viajes' => 'Crear nuevo viaje'
            ],
            'PUT' => [
                '/api/viajes/{id}' => 'Actualizar viaje'
            ],
            'DELETE' => [
                '/api/viajes/{id}' => 'Eliminar viaje'
            ]
        ]
    ];
    
    $response->getBody()->write(json_encode($data));
    return $response;
});
?>
