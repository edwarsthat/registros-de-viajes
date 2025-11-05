<?php
/**
 * Crear nuevo viaje
 * 
 * Formulario para registrar un nuevo viaje en el sistema
 */

session_start();

// Verificar si está logueado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../index.php');
    exit;
}

// Incluir conexión a la base de datos
require_once '../db.php';

// Variables para manejar errores y mensajes
$errores = [];
$exito = false;

// Obtener lista de carros y ciudades activas
try {
    $carros = $pdo->query("SELECT idcarro, placa, color FROM carro ORDER BY placa")->fetchAll();
    $ciudades = $pdo->query("SELECT idciudad, nombre FROM ciudad WHERE activo = 1 ORDER BY nombre")->fetchAll();
} catch (PDOException $e) {
    die('Error al obtener datos: ' . $e->getMessage());
}

// Procesar el formulario cuando se envíe
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener y sanitizar datos del formulario
    $idcarro = $_POST['idcarro'] ?? '';
    $idciudad_origen = $_POST['idciudad_origen'] ?? '';
    $idciudad_destino = $_POST['idciudad_destino'] ?? '';
    $tiempo_horas = $_POST['tiempo_horas'] ?? '';
    
    // VALIDACIONES
    
    // 1. Validar que el carro esté seleccionado
    if (empty($idcarro)) {
        $errores[] = 'Debe seleccionar un carro';
    }
    
    // 2. Validar que la ciudad origen esté seleccionada
    if (empty($idciudad_origen)) {
        $errores[] = 'Debe seleccionar una ciudad de origen';
    }
    
    // 3. Validar que la ciudad destino esté seleccionada
    if (empty($idciudad_destino)) {
        $errores[] = 'Debe seleccionar una ciudad de destino';
    }
    
    // 4. Validar que las ciudades sean diferentes
    if (!empty($idciudad_origen) && !empty($idciudad_destino) && $idciudad_origen === $idciudad_destino) {
        $errores[] = 'La ciudad de origen y destino deben ser diferentes';
    }
    
    // 5. Validar tiempo en horas
    if (empty($tiempo_horas)) {
        $errores[] = 'El tiempo de viaje es obligatorio';
    } elseif (!is_numeric($tiempo_horas) || $tiempo_horas <= 0) {
        $errores[] = 'El tiempo debe ser un número mayor a 0';
    }
    
    // Si no hay errores, proceder a guardar
    if (empty($errores)) {
        try {
            // Insertar viaje en la base de datos
            $stmt = $pdo->prepare("INSERT INTO viaje (idcarro, idciudad_origen, idciudad_destino, tiempo_horas) VALUES (?, ?, ?, ?)");
            $stmt->execute([$idcarro, $idciudad_origen, $idciudad_destino, $tiempo_horas]);
            
            $exito = true;
            
            // Redirigir al listado después de 2 segundos
            header('Refresh: 2; URL=listar.php?msg=Viaje creado exitosamente');
        } catch (PDOException $e) {
            $errores[] = 'Error al crear el viaje: ' . $e->getMessage();
        }
    }
}

// Configurar título de la página
$titulo = 'Crear Viaje';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($titulo) ?></title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- CSS personalizado -->
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container-fluid">
        <a class="navbar-brand" href="../usuarios/listar.php">Sistema de Gestión</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="../usuarios/listar.php">Usuarios</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../carros/listar.php">Carros</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="listar.php">Viajes</a>
                </li>
            </ul>
            <ul class="navbar-nav">
                <li class="nav-item">
                    <span class="navbar-text text-white me-3 d-flex align-items-center">
                        <i class="bi bi-person-circle me-2"></i>
                        <?= htmlspecialchars($_SESSION['usuario_nombre'] ?? 'Usuario') ?>
                    </span>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../logout.php">Cerrar Sesión</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Contenido Principal -->
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Crear Nuevo Viaje</h4>
                </div>
                <div class="card-body">
                    
                    <!-- Mostrar mensaje de éxito -->
                    <?php if ($exito): ?>
                        <div class="alert alert-success" role="alert">
                            <strong>¡Éxito!</strong> El viaje ha sido creado correctamente.
                            <br>Redirigiendo al listado...
                        </div>
                    <?php endif; ?>
                    
                    <!-- Mostrar errores de validación -->
                    <?php if (!empty($errores)): ?>
                        <div class="alert alert-danger" role="alert">
                            <strong>¡Error!</strong> Por favor corrige los siguientes errores:
                            <ul class="mb-0 mt-2">
                                <?php foreach ($errores as $error): ?>
                                    <li><?= htmlspecialchars($error) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Formulario -->
                    <form method="POST" action="" class="needs-validation" novalidate>
                        
                        <!-- Campo: Carro -->
                        <div class="mb-3">
                            <label for="idcarro" class="form-label">Vehículo <span class="text-danger">*</span></label>
                            <select class="form-select" id="idcarro" name="idcarro" required>
                                <option value="">Seleccione un vehículo</option>
                                <?php foreach ($carros as $carro): ?>
                                <option value="<?= $carro['idcarro'] ?>" <?= (($_POST['idcarro'] ?? '') == $carro['idcarro']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($carro['placa']) ?> - <?= htmlspecialchars($carro['color']) ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="form-text">Seleccione el vehículo que realizará el viaje</div>
                        </div>
                        
                        <!-- Campo: Ciudad Origen -->
                        <div class="mb-3">
                            <label for="idciudad_origen" class="form-label">Ciudad Origen <span class="text-danger">*</span></label>
                            <select class="form-select" id="idciudad_origen" name="idciudad_origen" required>
                                <option value="">Seleccione ciudad de origen</option>
                                <?php foreach ($ciudades as $ciudad): ?>
                                <option value="<?= $ciudad['idciudad'] ?>" <?= (($_POST['idciudad_origen'] ?? '') == $ciudad['idciudad']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($ciudad['nombre']) ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="form-text">Ciudad desde donde inicia el viaje</div>
                        </div>
                        
                        <!-- Campo: Ciudad Destino -->
                        <div class="mb-3">
                            <label for="idciudad_destino" class="form-label">Ciudad Destino <span class="text-danger">*</span></label>
                            <select class="form-select" id="idciudad_destino" name="idciudad_destino" required>
                                <option value="">Seleccione ciudad de destino</option>
                                <?php foreach ($ciudades as $ciudad): ?>
                                <option value="<?= $ciudad['idciudad'] ?>" <?= (($_POST['idciudad_destino'] ?? '') == $ciudad['idciudad']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($ciudad['nombre']) ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="form-text">Ciudad donde finaliza el viaje</div>
                        </div>
                        
                        <!-- Campo: Tiempo en Horas -->
                        <div class="mb-3">
                            <label for="tiempo_horas" class="form-label">Tiempo de Viaje (horas) <span class="text-danger">*</span></label>
                            <input 
                                type="number" 
                                class="form-control" 
                                id="tiempo_horas" 
                                name="tiempo_horas" 
                                value="<?= htmlspecialchars($_POST['tiempo_horas'] ?? '') ?>"
                                required
                                min="1"
                                step="1"
                                placeholder="Ej: 8"
                            >
                            <div class="form-text">Duración estimada del viaje en horas</div>
                        </div>
                        
                        <!-- Botones -->
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Guardar Viaje
                            </button>
                            <a href="listar.php" class="btn btn-secondary">
                                <i class="bi bi-x-circle"></i> Cancelar
                            </a>
                        </div>
                        
                    </form>
                    
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Footer -->
<footer class="bg-light text-center text-lg-start mt-5">
    <div class="text-center p-3">
        © <?php echo date('Y'); ?> Sistema de Gestión - Todos los derechos reservados
    </div>
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- JavaScript personalizado -->
<script src="../assets/js/main.js"></script>

</body>
</html>
