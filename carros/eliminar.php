<?php
/**
 * Eliminar carro
 * 
 * Este archivo procesará la eliminación de un carro
 */

session_start();

// Verificar si está logueado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../index.php');
    exit;
}

// Incluir conexión a la base de datos
require_once '../db.php';

// Obtener ID del carro a eliminar
$id = $_GET['id'] ?? $_POST['id'] ?? null;

if (!$id) {
    header('Location: listar.php?error=ID no especificado');
    exit;
}

// Si se confirma la eliminación (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Verificar si el carro tiene viajes asociados
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM viaje WHERE idcarro = ?");
        $stmt->execute([$id]);
        $tiene_viajes = $stmt->fetchColumn() > 0;
        
        if ($tiene_viajes) {
            header('Location: listar.php?error=No se puede eliminar el carro porque tiene viajes asociados');
            exit;
        }
        
        // Eliminar el carro
        $stmt = $pdo->prepare("DELETE FROM carro WHERE idcarro = ?");
        $stmt->execute([$id]);
        
        header('Location: listar.php?msg=Carro eliminado exitosamente');
        exit;
    } catch (PDOException $e) {
        header('Location: listar.php?error=Error al eliminar el carro: ' . $e->getMessage());
        exit;
    }
}

// Obtener datos del carro para mostrar en la confirmación
try {
    $stmt = $pdo->prepare("SELECT * FROM carro WHERE idcarro = ?");
    $stmt->execute([$id]);
    $carro = $stmt->fetch();
    
    if (!$carro) {
        header('Location: listar.php?error=Carro no encontrado');
        exit;
    }
} catch (PDOException $e) {
    die('Error al obtener carro: ' . $e->getMessage());
}

$titulo = 'Eliminar Carro';
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
        <div class="col-md-6">
            <div class="card shadow border-danger">
                <div class="card-header bg-danger text-white">
                    <h4 class="mb-0">⚠️ Confirmar Eliminación</h4>
                </div>
                <div class="card-body">
                    <div class="alert alert-warning">
                        <strong>¡Atención!</strong> Esta acción no se puede deshacer.
                    </div>
                    
                    <p class="mb-3">¿Está seguro que desea eliminar el siguiente carro?</p>
                    
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5>Datos del carro:</h5>
                            <ul class="list-unstyled mb-0">
                                <li><strong>ID:</strong> <?= $carro['idcarro'] ?></li>
                                <li><strong>Placa:</strong> <?= htmlspecialchars($carro['placa']) ?></li>
                                <li><strong>Color:</strong> <?= htmlspecialchars($carro['color']) ?></li>
                                <li><strong>Fecha Ingreso:</strong> <?= date('d/m/Y H:i', strtotime($carro['fecha_ingreso'])) ?></li>
                            </ul>
                        </div>
                    </div>
                    
                    <form method="POST" action="">
                        <input type="hidden" name="id" value="<?= htmlspecialchars($id) ?>">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-danger">
                                <i class="bi bi-trash"></i> Sí, Eliminar
                            </button>
                            <a href="listar.php" class="btn btn-secondary">
                                <i class="bi bi-x-circle"></i> No, Cancelar
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
