<?php
/**
 * API REST - Punto de Entrada con Códigos HTTP
 * 
 * Implementa códigos de estado HTTP según los escenarios:
 * - 200 OK: Solicitud exitosa
 * - 201 Created: Recurso creado exitosamente
 * - 204 No Content: Éxito sin contenido
 * - 400 Bad Request: Solicitud inválida
 * - 401 Unauthorized: No autorizado
 * - 404 Not Found: Recurso no encontrado
 * - 409 Conflict: Conflicto (ej: placa duplicada)
 * - 500 Internal Server Error: Error del servidor
 */

require __DIR__ . '/vendor/autoload.php';

use Slim\Factory\AppFactory;

// Crear aplicación
$app = AppFactory::create();

// Agregar error middleware
$errorMiddleware = $app->addErrorMiddleware(true, true, true);

// CORS middleware
$app->add(function ($request, $handler) {
    $response = $handler->handle($request);
    return $response
        ->withHeader('Access-Control-Allow-Origin', '*')
        ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
        ->withHeader('Access-Control-Allow-Headers', 'Content-Type')
        ->withHeader('Content-Type', 'application/json');
});

// Función auxiliar para cargar variables de entorno
function loadEnv() {
    $env_path = __DIR__ . '/../.env';
    if (file_exists($env_path)) {
        $lines = file($env_path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            if (strpos($line, '=') !== false && strpos(trim($line), '#') !== 0) {
                list($key, $value) = explode('=', $line, 2);
                $_ENV[trim($key)] = trim($value);
            }
        }
    }
}

// Función auxiliar para conectar a BD
function getDbConnection() {
    loadEnv();
    $host = $_ENV['DB_HOST'] ?? 'localhost';
    $dbname = $_ENV['DB_NAME'] ?? 'prueba_tecnica';
    $user = $_ENV['DB_USER'] ?? 'root';
    $pass = $_ENV['DB_PASS'] ?? '';

    try {
        return new PDO(
            "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
            $user,
            $pass,
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );
    } catch (PDOException $e) {
        throw new Exception("Error de conexión a BD: " . $e->getMessage());
    }
}

// ============================================================
// RUTA: GET /
// 200 OK - Información de la API
// ============================================================
$app->get('/', function ($request, $response) {
    $data = [
        'status' => 'success',
        'message' => '🚀 API REST - Sistema de Gestión de Viajes',
        'version' => '1.0',
        'endpoints' => [
            'Viajes' => [
                'GET /api/viajes/por-placa/{placa}' => 'Obtener viajes por placa (200 OK)',
                'POST /api/viajes' => 'Crear nuevo viaje (201 Created)',
                'PUT /api/viajes/{id}' => 'Actualizar viaje (200 OK)',
                'DELETE /api/viajes/{id}' => 'Eliminar viaje (204 No Content)'
            ]
        ]
    ];
    $response->getBody()->write(json_encode($data));
    return $response->withStatus(200);
});

// ============================================================
// GET: /api/viajes/por-placa/{placa}
// 200 OK: Viajes encontrados
// 404 Not Found: Sin viajes para la placa
// 400 Bad Request: Placa inválida
// 500 Internal Server Error: Error BD
// ============================================================
$app->get('/api/viajes/por-placa/{placa}', function ($request, $response, $args) {
    try {
        // Validar placa
        $placa = trim($args['placa'] ?? '');
        if (empty($placa)) {
            return $response
                ->withStatus(400)
                ->withHeader('Content-Type', 'application/json')
                ->write(json_encode([
                    'status' => 'error',
                    'code' => 400,
                    'message' => 'Bad Request: Placa no proporcionada',
                    'error' => 'La placa es requerida en la URL'
                ]));
        }

        // Validar formato placa (alfanumérico)
        if (!preg_match('/^[A-Z0-9]{3,20}$/i', $placa)) {
            return $response
                ->withStatus(400)
                ->withHeader('Content-Type', 'application/json')
                ->write(json_encode([
                    'status' => 'error',
                    'code' => 400,
                    'message' => 'Bad Request: Formato de placa inválido',
                    'error' => 'La placa debe contener solo letras y números'
                ]));
        }

        $placa = strtoupper($placa);
        $pdo = getDbConnection();

        // Consulta con JOIN
        $sql = "
            SELECT 
                v.idviaje,
                c.idcarro,
                c.placa,
                c.color,
                v.idciudad_origen,
                co.nombre as ciudad_origen,
                v.idciudad_destino,
                cd.nombre as ciudad_destino,
                v.tiempo_horas,
                v.fecha
            FROM viaje v
            INNER JOIN carro c ON v.idcarro = c.idcarro
            LEFT JOIN ciudad co ON v.idciudad_origen = co.idciudad
            LEFT JOIN ciudad cd ON v.idciudad_destino = cd.idciudad
            WHERE UPPER(c.placa) = UPPER(?)
            ORDER BY v.fecha DESC
        ";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([$placa]);
        $viajes = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // 200 OK: Viajes encontrados
        if (count($viajes) > 0) {
            $result = [
                'status' => 'success',
                'code' => 200,
                'message' => 'OK: Viajes encontrados',
                'placa' => $placa,
                'count' => count($viajes),
                'data' => $viajes
            ];
            $response->getBody()->write(json_encode($result));
            return $response->withStatus(200);
        }

        // 404 Not Found: Sin viajes
        $result = [
            'status' => 'info',
            'code' => 404,
            'message' => 'Not Found: No hay viajes registrados',
            'placa' => $placa,
            'count' => 0,
            'data' => []
        ];
        $response->getBody()->write(json_encode($result));
        return $response->withStatus(404);

    } catch (Exception $e) {
        // 500 Internal Server Error
        $response->getBody()->write(json_encode([
            'status' => 'error',
            'code' => 500,
            'message' => 'Internal Server Error',
            'error' => $e->getMessage()
        ]));
        return $response->withStatus(500);
    }
});

// ============================================================
// POST: /api/viajes
// 201 Created: Viaje creado exitosamente
// 400 Bad Request: Datos inválidos o faltantes
// 409 Conflict: Conflicto (ej: ciudades iguales)
// 500 Internal Server Error: Error BD
// ============================================================
$app->post('/api/viajes', function ($request, $response) {
    try {
        $data = json_decode($request->getBody(), true);

        // Validar campos requeridos
        $campos_requeridos = ['idcarro', 'idciudad_origen', 'idciudad_destino', 'tiempo_horas', 'fecha'];
        foreach ($campos_requeridos as $campo) {
            if (!isset($data[$campo]) || empty($data[$campo])) {
                return $response
                    ->withStatus(400)
                    ->withHeader('Content-Type', 'application/json')
                    ->write(json_encode([
                        'status' => 'error',
                        'code' => 400,
                        'message' => 'Bad Request: Campo requerido faltante',
                        'error' => "El campo '$campo' es requerido",
                        'campos_requeridos' => $campos_requeridos
                    ]));
            }
        }

        // Validar que origen != destino
        if ($data['idciudad_origen'] == $data['idciudad_destino']) {
            return $response
                ->withStatus(409)
                ->withHeader('Content-Type', 'application/json')
                ->write(json_encode([
                    'status' => 'error',
                    'code' => 409,
                    'message' => 'Conflict: Validación fallida',
                    'error' => 'La ciudad de origen debe ser diferente a la de destino'
                ]));
        }

        // Validar tiempo
        if ($data['tiempo_horas'] <= 0) {
            return $response
                ->withStatus(400)
                ->withHeader('Content-Type', 'application/json')
                ->write(json_encode([
                    'status' => 'error',
                    'code' => 400,
                    'message' => 'Bad Request: Validación fallida',
                    'error' => 'El tiempo debe ser mayor a 0'
                ]));
        }

        $pdo = getDbConnection();

        // Insertar viaje
        $sql = "
            INSERT INTO viaje (idcarro, idciudad_origen, idciudad_destino, tiempo_horas, fecha)
            VALUES (?, ?, ?, ?, ?)
        ";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $data['idcarro'],
            $data['idciudad_origen'],
            $data['idciudad_destino'],
            $data['tiempo_horas'],
            $data['fecha']
        ]);

        $id = $pdo->lastInsertId();

        // 201 Created
        $result = [
            'status' => 'success',
            'code' => 201,
            'message' => 'Created: Viaje creado exitosamente',
            'id' => $id,
            'data' => [
                'idviaje' => $id,
                'idcarro' => $data['idcarro'],
                'idciudad_origen' => $data['idciudad_origen'],
                'idciudad_destino' => $data['idciudad_destino'],
                'tiempo_horas' => $data['tiempo_horas'],
                'fecha' => $data['fecha']
            ]
        ];

        $response->getBody()->write(json_encode($result));
        return $response->withStatus(201);

    } catch (Exception $e) {
        // 500 Internal Server Error
        $response->getBody()->write(json_encode([
            'status' => 'error',
            'code' => 500,
            'message' => 'Internal Server Error',
            'error' => $e->getMessage()
        ]));
        return $response->withStatus(500);
    }
});

// ============================================================
// PUT: /api/viajes/{id}
// 200 OK: Viaje actualizado
// 404 Not Found: Viaje no existe
// 400 Bad Request: Datos inválidos
// 500 Internal Server Error: Error BD
// ============================================================
$app->put('/api/viajes/{id}', function ($request, $response, $args) {
    try {
        $id = $args['id'];
        $data = json_decode($request->getBody(), true);

        if (empty($data)) {
            return $response
                ->withStatus(400)
                ->withHeader('Content-Type', 'application/json')
                ->write(json_encode([
                    'status' => 'error',
                    'code' => 400,
                    'message' => 'Bad Request: Sin datos para actualizar'
                ]));
        }

        $pdo = getDbConnection();

        // Verificar que el viaje existe
        $check_sql = "SELECT idviaje FROM viaje WHERE idviaje = ?";
        $check_stmt = $pdo->prepare($check_sql);
        $check_stmt->execute([$id]);
        if (!$check_stmt->fetch()) {
            return $response
                ->withStatus(404)
                ->withHeader('Content-Type', 'application/json')
                ->write(json_encode([
                    'status' => 'error',
                    'code' => 404,
                    'message' => 'Not Found: El viaje no existe'
                ]));
        }

        // Construir actualización dinámica
        $campos = [];
        $params = [];
        if (isset($data['tiempo_horas'])) {
            $campos[] = 'tiempo_horas = ?';
            $params[] = $data['tiempo_horas'];
        }
        if (isset($data['fecha'])) {
            $campos[] = 'fecha = ?';
            $params[] = $data['fecha'];
        }

        if (empty($campos)) {
            return $response
                ->withStatus(400)
                ->withHeader('Content-Type', 'application/json')
                ->write(json_encode([
                    'status' => 'error',
                    'code' => 400,
                    'message' => 'Bad Request: Ningún campo válido para actualizar'
                ]));
        }

        $params[] = $id;
        $sql = "UPDATE viaje SET " . implode(', ', $campos) . " WHERE idviaje = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);

        // 200 OK
        $result = [
            'status' => 'success',
            'code' => 200,
            'message' => 'OK: Viaje actualizado exitosamente',
            'id' => $id
        ];

        $response->getBody()->write(json_encode($result));
        return $response->withStatus(200);

    } catch (Exception $e) {
        // 500 Internal Server Error
        $response->getBody()->write(json_encode([
            'status' => 'error',
            'code' => 500,
            'message' => 'Internal Server Error',
            'error' => $e->getMessage()
        ]));
        return $response->withStatus(500);
    }
});

// ============================================================
// DELETE: /api/viajes/{id}
// 204 No Content: Eliminado exitosamente
// 404 Not Found: Viaje no existe
// 500 Internal Server Error: Error BD
// ============================================================
$app->delete('/api/viajes/{id}', function ($request, $response, $args) {
    try {
        $id = $args['id'];
        $pdo = getDbConnection();

        // Verificar que el viaje existe
        $check_sql = "SELECT idviaje FROM viaje WHERE idviaje = ?";
        $check_stmt = $pdo->prepare($check_sql);
        $check_stmt->execute([$id]);
        if (!$check_stmt->fetch()) {
            return $response
                ->withStatus(404)
                ->withHeader('Content-Type', 'application/json')
                ->write(json_encode([
                    'status' => 'error',
                    'code' => 404,
                    'message' => 'Not Found: El viaje no existe'
                ]));
        }

        // Eliminar viaje
        $sql = "DELETE FROM viaje WHERE idviaje = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);

        // 204 No Content
        return $response->withStatus(204);

    } catch (Exception $e) {
        // 500 Internal Server Error
        $response->getBody()->write(json_encode([
            'status' => 'error',
            'code' => 500,
            'message' => 'Internal Server Error',
            'error' => $e->getMessage()
        ]));
        return $response->withStatus(500);
    }
});

// Ejecutar
$app->run();
?>
