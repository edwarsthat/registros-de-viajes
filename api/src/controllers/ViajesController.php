<?php
/**
 * Controlador de Viajes
 * 
 * Maneja todas las operaciones relacionadas con viajes
 */

require_once __DIR__ . '/../config/Database.php';

use App\Config\Database;

class ViajesController
{
    private static $db = null;

    /**
     * Inicializar la conexión a la base de datos
     */
    private static function init()
    {
        if (self::$db === null) {
            self::$db = Database::getInstance()->getConnection();
        }
    }

    /**
     * Obtener todos los viajes
     */
    public static function obtenerTodos()
    {
        self::init();

        try {
            $sql = "
                SELECT 
                    v.idviaje,
                    v.idcarro,
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
                ORDER BY v.fecha DESC
            ";

            $stmt = self::$db->prepare($sql);
            $stmt->execute();
            $viajes = $stmt->fetchAll();

            return [
                'status' => 'success',
                'message' => 'Viajes obtenidos correctamente',
                'count' => count($viajes),
                'data' => $viajes
            ];
        } catch (Exception $e) {
            return [
                'status' => 'error',
                'message' => 'Error al obtener viajes',
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Obtener viajes por placa del vehículo
     * 
     * @param string $placa - Placa del vehículo
     * @return array Resultado de la consulta
     */
    public static function obtenerViajesPorPlaca($placa)
    {
        self::init();

        try {
            $sql = "
                SELECT 
                    v.idviaje,
                    v.idcarro,
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

            $stmt = self::$db->prepare($sql);
            $stmt->execute([$placa]);
            $viajes = $stmt->fetchAll();

            if (empty($viajes)) {
                return [
                    'status' => 'info',
                    'message' => 'No hay viajes registrados para el vehículo con placa: ' . $placa,
                    'placa' => $placa,
                    'count' => 0,
                    'data' => []
                ];
            }

            return [
                'status' => 'success',
                'message' => 'Viajes encontrados para la placa: ' . $placa,
                'placa' => $placa,
                'count' => count($viajes),
                'data' => $viajes
            ];
        } catch (Exception $e) {
            return [
                'status' => 'error',
                'message' => 'Error al obtener viajes por placa',
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Obtener un viaje por ID
     */
    public static function obtenerPorId($id)
    {
        self::init();

        try {
            $sql = "
                SELECT 
                    v.idviaje,
                    v.idcarro,
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
                WHERE v.idviaje = ?
            ";

            $stmt = self::$db->prepare($sql);
            $stmt->execute([$id]);
            $viaje = $stmt->fetch();

            if (!$viaje) {
                return [
                    'status' => 'error',
                    'message' => 'Viaje no encontrado'
                ];
            }

            return [
                'status' => 'success',
                'message' => 'Viaje obtenido correctamente',
                'data' => $viaje
            ];
        } catch (Exception $e) {
            return [
                'status' => 'error',
                'message' => 'Error al obtener el viaje',
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Crear un nuevo viaje
     */
    public static function crear($data)
    {
        self::init();

        // Validar datos
        if (empty($data['idcarro']) || empty($data['idciudad_origen']) || 
            empty($data['idciudad_destino']) || empty($data['tiempo_horas']) || empty($data['fecha'])) {
            return [
                'status' => 'error',
                'message' => 'Faltan campos requeridos',
                'required' => ['idcarro', 'idciudad_origen', 'idciudad_destino', 'tiempo_horas', 'fecha']
            ];
        }

        // Validar que origen sea diferente de destino
        if ($data['idciudad_origen'] == $data['idciudad_destino']) {
            return [
                'status' => 'error',
                'message' => 'La ciudad de origen debe ser diferente a la ciudad de destino'
            ];
        }

        try {
            $sql = "
                INSERT INTO viaje (idcarro, idciudad_origen, idciudad_destino, tiempo_horas, fecha)
                VALUES (?, ?, ?, ?, ?)
            ";

            $stmt = self::$db->prepare($sql);
            $result = $stmt->execute([
                $data['idcarro'],
                $data['idciudad_origen'],
                $data['idciudad_destino'],
                $data['tiempo_horas'],
                $data['fecha']
            ]);

            if ($result) {
                $id = self::$db->lastInsertId();
                return [
                    'status' => 'success',
                    'message' => 'Viaje creado exitosamente',
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
            }

            return [
                'status' => 'error',
                'message' => 'No se pudo crear el viaje'
            ];
        } catch (Exception $e) {
            return [
                'status' => 'error',
                'message' => 'Error al crear el viaje',
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Actualizar un viaje
     */
    public static function actualizar($id, $data)
    {
        self::init();

        try {
            $fields = [];
            $params = [];

            if (isset($data['idcarro'])) {
                $fields[] = 'idcarro = ?';
                $params[] = $data['idcarro'];
            }
            if (isset($data['idciudad_origen'])) {
                $fields[] = 'idciudad_origen = ?';
                $params[] = $data['idciudad_origen'];
            }
            if (isset($data['idciudad_destino'])) {
                $fields[] = 'idciudad_destino = ?';
                $params[] = $data['idciudad_destino'];
            }
            if (isset($data['tiempo_horas'])) {
                $fields[] = 'tiempo_horas = ?';
                $params[] = $data['tiempo_horas'];
            }
            if (isset($data['fecha'])) {
                $fields[] = 'fecha = ?';
                $params[] = $data['fecha'];
            }

            if (empty($fields)) {
                return [
                    'status' => 'error',
                    'message' => 'No hay campos para actualizar'
                ];
            }

            $params[] = $id;

            $sql = "UPDATE viaje SET " . implode(', ', $fields) . " WHERE idviaje = ?";
            $stmt = self::$db->prepare($sql);
            $result = $stmt->execute($params);

            if ($result) {
                return [
                    'status' => 'success',
                    'message' => 'Viaje actualizado exitosamente'
                ];
            }

            return [
                'status' => 'error',
                'message' => 'No se pudo actualizar el viaje'
            ];
        } catch (Exception $e) {
            return [
                'status' => 'error',
                'message' => 'Error al actualizar el viaje',
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Eliminar un viaje
     */
    public static function eliminar($id)
    {
        self::init();

        try {
            $sql = "DELETE FROM viaje WHERE idviaje = ?";
            $stmt = self::$db->prepare($sql);
            $result = $stmt->execute([$id]);

            if ($result && $stmt->rowCount() > 0) {
                return [
                    'status' => 'success',
                    'message' => 'Viaje eliminado exitosamente'
                ];
            }

            return [
                'status' => 'error',
                'message' => 'Viaje no encontrado'
            ];
        } catch (Exception $e) {
            return [
                'status' => 'error',
                'message' => 'Error al eliminar el viaje',
                'error' => $e->getMessage()
            ];
        }
    }
}
?>
