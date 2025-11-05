<?php
/**
 * Listado de viajes
 * 
 * Esta página mostrará una tabla con todos los viajes registrados
 * usando DataTables para búsqueda, paginación y ordenamiento.
 */

session_start();

// Verificar si está logueado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../index.php');
    exit;
}

// Incluir conexión a la base de datos
require_once '../db.php';

$titulo = 'Listado de Viajes';

// Obtener viajes con información de carros y ciudades
try {
    $stmt = $pdo->query("
        SELECT 
            v.idviaje,
            v.tiempo_horas,
            v.fecha,
            c.placa,
            c.color,
            co.nombre AS ciudad_origen,
            cd.nombre AS ciudad_destino
        FROM viaje v
        INNER JOIN carro c ON v.idcarro = c.idcarro
        LEFT JOIN ciudad co ON v.idciudad_origen = co.idciudad
        LEFT JOIN ciudad cd ON v.idciudad_destino = cd.idciudad
        ORDER BY v.fecha DESC
    ");
    $viajes = $stmt->fetchAll();
} catch (PDOException $e) {
    die('Error al obtener viajes: ' . $e->getMessage());
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
    
    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    
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
<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Listado de Viajes</h2>
        <a href="crear.php" class="btn btn-success">
            + Nuevo Viaje
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
                <table id="tablaViajes" class="table table-striped table-hover" style="width:100%">
                    <thead class="table-primary">
                        <tr>
                            <th>ID</th>
                            <th>Placa Vehículo</th>
                            <th>Color</th>
                            <th>Ciudad Origen</th>
                            <th>Ciudad Destino</th>
                            <th>Tiempo (horas)</th>
                            <th>Fecha</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($viajes)): ?>
                        <?php foreach ($viajes as $viaje): ?>
                        <tr>
                            <td><?= $viaje['idviaje'] ?></td>
                            <td><strong><?= htmlspecialchars($viaje['placa']) ?></strong></td>
                            <td>
                                <span class="badge" style="background-color: <?= htmlspecialchars(strtolower($viaje['color'])) ?>; color: black;">
                                    <?= htmlspecialchars($viaje['color']) ?>
                                </span>
                            </td>
                            <td><?= htmlspecialchars($viaje['ciudad_origen'] ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars($viaje['ciudad_destino'] ?? 'N/A') ?></td>
                            <td><?= $viaje['tiempo_horas'] ?> hrs</td>
                            <td><?= date('d/m/Y H:i', strtotime($viaje['fecha'])) ?></td>
                            <td>
                                <a href="editar.php?id=<?= $viaje['idviaje'] ?>" class="btn btn-sm btn-warning">Editar</a>
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

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<!-- JavaScript personalizado -->
<script src="../assets/js/main.js"></script>

<!-- Inicializar DataTable -->
<script>
$(document).ready(function() {
    $('#tablaViajes').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
        },
        order: [[0, 'desc']], // Ordenar por ID descendente
        pageLength: 10,
        responsive: true
    });
});
</script>

</body>
</html>
