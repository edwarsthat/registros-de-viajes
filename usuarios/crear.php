<?php
/**
 * Crear nuevo usuario
 * 
 * Formulario para registrar un nuevo usuario en el sistema
 */

// Iniciar sesión
session_start();

// Verificar si está logueado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../index.php');
    exit;
}

// Variables para manejar errores y mensajes
$errores = [];
$exito = false;

// Procesar el formulario cuando se envíe
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener y sanitizar datos del formulario
    $nombre = trim($_POST['nombre'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $password_confirm = $_POST['password_confirm'] ?? '';
    $rol = $_POST['rol'] ?? 'Usuario';
    
    // VALIDACIONES
    
    // 1. Validar que el nombre no esté vacío
    if (empty($nombre)) {
        $errores[] = 'El nombre es obligatorio';
    }
    
    // 2. Validar que el email sea válido
    if (empty($email)) {
        $errores[] = 'El email es obligatorio';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errores[] = 'El email no tiene un formato válido';
    }
    
    // 3. Validar la contraseña
    if (empty($password)) {
        $errores[] = 'La contraseña es obligatoria';
    } elseif (strlen($password) < 6) {
        $errores[] = 'La contraseña debe tener al menos 6 caracteres';
    }
    
    // 4. Validar que las contraseñas coincidan
    if ($password !== $password_confirm) {
        $errores[] = 'Las contraseñas no coinciden';
    }
    
    // Si no hay errores, proceder a guardar (simulado por ahora)
    if (empty($errores)) {
        // TODO: Aquí se guardará en la base de datos cuando esté lista
        // Por ahora solo mostramos mensaje de éxito
        
        // En producción harías:
        // require_once '../db.php';
        // $password_hash = password_hash($password, PASSWORD_DEFAULT);
        // $stmt = $pdo->prepare("INSERT INTO usuarios (nombre, email, password, rol) VALUES (?, ?, ?, ?)");
        // $stmt->execute([$nombre, $email, $password_hash, $rol]);
        
        $exito = true;
        
        // Redirigir al listado después de 2 segundos
        header('Refresh: 2; URL=listar.php?msg=Usuario creado exitosamente');
    }
}

// Configurar título de la página
$titulo = 'Crear Usuario';
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
        <a class="navbar-brand" href="listar.php">Sistema de Gestión</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link active" href="listar.php">Usuarios</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../carros/listar.php">Carros</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../viajes/listar.php">Viajes</a>
                </li>
            </ul>
            <ul class="navbar-nav">
                <li class="nav-item">
                    <span class="navbar-text text-white me-3">
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
                    <h4 class="mb-0">Crear Nuevo Usuario</h4>
                </div>
                <div class="card-body">
                    
                    <!-- Mostrar mensaje de éxito -->
                    <?php if ($exito): ?>
                        <div class="alert alert-success" role="alert">
                            <strong>¡Éxito!</strong> El usuario ha sido creado correctamente.
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
                        
                        <!-- Campo: Nombre -->
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre Completo <span class="text-danger">*</span></label>
                            <input 
                                type="text" 
                                class="form-control" 
                                id="nombre" 
                                name="nombre" 
                                value="<?= htmlspecialchars($_POST['nombre'] ?? '') ?>"
                                required
                                placeholder="Ej: Juan Pérez"
                            >
                            <div class="form-text">Ingrese el nombre completo del usuario</div>
                        </div>
                        
                        <!-- Campo: Email -->
                        <div class="mb-3">
                            <label for="email" class="form-label">Correo Electrónico <span class="text-danger">*</span></label>
                            <input 
                                type="email" 
                                class="form-control" 
                                id="email" 
                                name="email" 
                                value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                                required
                                placeholder="ejemplo@correo.com"
                            >
                            <div class="form-text">El email debe ser único en el sistema</div>
                        </div>
                        
                        <!-- Campo: Contraseña -->
                        <div class="mb-3">
                            <label for="password" class="form-label">Contraseña <span class="text-danger">*</span></label>
                            <input 
                                type="password" 
                                class="form-control" 
                                id="password" 
                                name="password" 
                                required
                                minlength="6"
                                placeholder="Mínimo 6 caracteres"
                            >
                            <div class="form-text">Debe tener al menos 6 caracteres</div>
                        </div>
                        
                        <!-- Campo: Confirmar Contraseña -->
                        <div class="mb-3">
                            <label for="password_confirm" class="form-label">Confirmar Contraseña <span class="text-danger">*</span></label>
                            <input 
                                type="password" 
                                class="form-control" 
                                id="password_confirm" 
                                name="password_confirm" 
                                required
                                minlength="6"
                                placeholder="Repita la contraseña"
                            >
                        </div>
                        
                        <!-- Campo: Rol -->
                        <div class="mb-3">
                            <label for="rol" class="form-label">Rol del Usuario</label>
                            <select class="form-select" id="rol" name="rol">
                                <option value="Usuario" <?= (($_POST['rol'] ?? '') === 'Usuario') ? 'selected' : '' ?>>Usuario</option>
                                <option value="Administrador" <?= (($_POST['rol'] ?? '') === 'Administrador') ? 'selected' : '' ?>>Administrador</option>
                            </select>
                            <div class="form-text">Define los permisos del usuario en el sistema</div>
                        </div>
                        
                        <!-- Botones -->
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Guardar Usuario
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

<!-- Validación de formulario -->
<script>
// Validar que las contraseñas coincidan
document.querySelector('form').addEventListener('submit', function(e) {
    const password = document.getElementById('password').value;
    const passwordConfirm = document.getElementById('password_confirm').value;
    
    if (password !== passwordConfirm) {
        e.preventDefault();
        alert('Las contraseñas no coinciden');
        return false;
    }
});
</script>

</body>
</html>
