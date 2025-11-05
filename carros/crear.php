<?php
/**
 * Crear nuevo carro
 * 
 * Formulario para registrar un nuevo carro en el sistema
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

// Procesar el formulario cuando se envíe
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener y sanitizar datos del formulario
    $placa = strtoupper(trim($_POST['placa'] ?? ''));
    $color = trim($_POST['color'] ?? '');
    
    // VALIDACIONES
    
    // 1. Validar que la placa no esté vacía
    if (empty($placa)) {
        $errores[] = 'La placa es obligatoria';
    } elseif (strlen($placa) < 5 || strlen($placa) > 10) {
        $errores[] = 'La placa debe tener entre 5 y 10 caracteres';
    } else {
        // Verificar que la placa no exista
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM carro WHERE placa = ?");
        $stmt->execute([$placa]);
        if ($stmt->fetchColumn() > 0) {
            $errores[] = 'La placa ya está registrada en el sistema';
        }
    }
    
    // 2. Validar que el color no esté vacío
    if (empty($color)) {
        $errores[] = 'El color es obligatorio';
    }
    
    // Si no hay errores, proceder a guardar
    if (empty($errores)) {
        try {
            // Insertar carro en la base de datos
            $stmt = $pdo->prepare("INSERT INTO carro (placa, color) VALUES (?, ?)");
            $stmt->execute([$placa, $color]);
            
            $exito = true;
            
            // Redirigir al listado después de 2 segundos
            header('Refresh: 2; URL=listar.php?msg=Carro creado exitosamente');
        } catch (PDOException $e) {
            $errores[] = 'Error al crear el carro: ' . $e->getMessage();
        }
    }
}

// Configurar título de la página
$titulo = 'Crear Carro';
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
                    <a class="nav-link active" href="listar.php">Carros</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../viajes/listar.php">Viajes</a>
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
                    <h4 class="mb-0">Crear Nuevo Carro</h4>
                </div>
                <div class="card-body">
                    
                    <!-- Mostrar mensaje de éxito -->
                    <?php if ($exito): ?>
                        <div class="alert alert-success" role="alert">
                            <strong>¡Éxito!</strong> El carro ha sido creado correctamente.
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
                        
                        <!-- Campo: Placa -->
                        <div class="mb-3">
                            <label for="placa" class="form-label">Placa <span class="text-danger">*</span></label>
                            <input 
                                type="text" 
                                class="form-control text-uppercase" 
                                id="placa" 
                                name="placa" 
                                value="<?= htmlspecialchars($_POST['placa'] ?? '') ?>"
                                required
                                maxlength="10"
                                placeholder="Ej: ABC123"
                                style="text-transform: uppercase;"
                            >
                            <div class="form-text">Ingrese la placa del vehículo (5-10 caracteres)</div>
                        </div>
                        
                        <!-- Campo: Color -->
                        <div class="mb-3">
                            <label for="color" class="form-label">Color <span class="text-danger">*</span></label>
                            <select class="form-select" id="color" name="color" required>
                                <option value="">Seleccione un color</option>
                                <option value="Rojo" <?= (($_POST['color'] ?? '') === 'Rojo') ? 'selected' : '' ?>>Rojo</option>
                                <option value="Azul" <?= (($_POST['color'] ?? '') === 'Azul') ? 'selected' : '' ?>>Azul</option>
                                <option value="Verde" <?= (($_POST['color'] ?? '') === 'Verde') ? 'selected' : '' ?>>Verde</option>
                                <option value="Negro" <?= (($_POST['color'] ?? '') === 'Negro') ? 'selected' : '' ?>>Negro</option>
                                <option value="Blanco" <?= (($_POST['color'] ?? '') === 'Blanco') ? 'selected' : '' ?>>Blanco</option>
                                <option value="Gris" <?= (($_POST['color'] ?? '') === 'Gris') ? 'selected' : '' ?>>Gris</option>
                                <option value="Plata" <?= (($_POST['color'] ?? '') === 'Plata') ? 'selected' : '' ?>>Plata</option>
                                <option value="Amarillo" <?= (($_POST['color'] ?? '') === 'Amarillo') ? 'selected' : '' ?>>Amarillo</option>
                            </select>
                            <div class="form-text">Seleccione el color del vehículo</div>
                        </div>
                        
                        <!-- Botones -->
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Guardar Carro
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

<!-- Convertir placa a mayúsculas automáticamente -->
<script>
document.getElementById('placa').addEventListener('input', function(e) {
    e.target.value = e.target.value.toUpperCase();
});
</script>

</body>
</html>
