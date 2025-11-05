<?php
/**
 * Listado de carros
 * 
 * Esta página mostrará una tabla con todos los carros registrados
 */

session_start();

// Verificar si está logueado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../index.php');
    exit;
}

// Incluir conexión a la base de datos
require_once '../db.php';

$titulo = 'Listado de Carros';

// Obtener carros de la base de datos
try {
    $stmt = $pdo->query("SELECT * FROM carro ORDER BY idcarro DESC");
    $carros = $stmt->fetchAll();
} catch (PDOException $e) {
    die('Error al obtener carros: ' . $e->getMessage());
}
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
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Listado de Carros</h2>
        <a href="crear.php" class="btn btn-success">
            + Nuevo Carro
        </a>
    </div>

    <?php if (isset($_GET['msg'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($_GET['msg']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($_GET['error']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="card shadow">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-primary">
                        <tr>
                            <th>ID</th>
                            <th>Placa</th>
                            <th>Color</th>
                            <th>Fecha Ingreso</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($carros)): ?>
                        <tr>
                            <td colspan="5" class="text-center">No hay carros registrados</td>
                        </tr>
                        <?php else: ?>
                        <?php foreach ($carros as $carro): ?>
                        <tr>
                            <td><?= $carro['idcarro'] ?></td>
                            <td><strong><?= htmlspecialchars($carro['placa']) ?></strong></td>
                            <td>
                                <span class="badge" style="background-color: <?= htmlspecialchars(strtolower($carro['color'])) ?>; color: black;">
                                    <?= htmlspecialchars($carro['color']) ?>
                                </span>
                            </td>
                            <td><?= date('d/m/Y H:i', strtotime($carro['fecha_ingreso'])) ?></td>
                            <td>
                                <a href="editar.php?id=<?= $carro['idcarro'] ?>" class="btn btn-sm btn-warning">Editar</a>
                                <a href="eliminar.php?id=<?= $carro['idcarro'] ?>" class="btn btn-sm btn-danger" 
                                   onclick="return confirm('¿Está seguro de eliminar este carro?')">Eliminar</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
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
