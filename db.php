<?php
/**
 * Archivo de conexión a la base de datos MySQL
 * 
 * Carga las variables de entorno desde .env y establece
 * la conexión con la base de datos usando PDO.
 * 
 * Configuración:
 * - Carga de variables de entorno con vlucas/phpdotenv
 * - Conexión PDO con manejo de excepciones
 * - Charset UTF-8 para soporte de caracteres especiales
 */

// Cargar autoload de Composer
require_once __DIR__ . '/vendor/autoload.php';

// Cargar variables de entorno
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Obtener credenciales desde .env
$host = $_ENV['DB_HOST'];
$dbname = $_ENV['DB_NAME'];
$username = $_ENV['DB_USER'];
$password = $_ENV['DB_PASS'];

// Configurar DSN (Data Source Name)
$dsn = "mysql:host={$host};dbname={$dbname};charset=utf8mb4";

try {
    // Crear conexión PDO
    $pdo = new PDO($dsn, $username, $password);
    
    // Configurar modo de errores: lanzar excepciones
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Configurar modo de fetch por defecto (array asociativo)
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    
    // Deshabilitar emulación de prepared statements para mejor seguridad
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    
} catch (PDOException $e) {
    // Manejo de errores de conexión
    die("Error de conexión a la base de datos: " . $e->getMessage());
}

// La variable $pdo está disponible para ser usada en otros archivos
?>
