<?php
/**
 * Página principal de login
 * 
 * Este archivo mostrará el formulario de inicio de sesión
 * y validará las credenciales de los usuarios.
 */

session_start();

// Si ya está logueado, redirigir al dashboard
if (isset($_SESSION['usuario_id'])) {
    header('Location: usuarios/listar.php');
    exit;
}

// Incluir conexión a la base de datos
require_once 'db.php';

$error = '';

// Procesar el login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    try {
        // Buscar usuario por email
        $stmt = $pdo->prepare("SELECT * FROM usuario WHERE email = ? AND activo = 1");
        $stmt->execute([$email]);
        $usuario = $stmt->fetch();
        
        // Verificar si el usuario existe y la contraseña es correcta
        if ($usuario && password_verify($password, $usuario['password'])) {
            // Login exitoso
            $_SESSION['usuario_id'] = $usuario['idusuario'];
            $_SESSION['usuario_nombre'] = $usuario['nombre'];
            $_SESSION['usuario_email'] = $usuario['email'];
            header('Location: usuarios/listar.php');
            exit;
        } else {
            $error = 'Credenciales incorrectas o usuario inactivo';
        }
    } catch (PDOException $e) {
        $error = 'Error al iniciar sesión. Intente nuevamente.';
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistema de Gestión</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body class="bg-light">
    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-md-5">
                <div class="card shadow">
                    <div class="card-body p-5">
                        <h3 class="card-title text-center mb-4">Iniciar Sesión</h3>
                        
                        <?php if ($error): ?>
                            <div class="alert alert-danger" role="alert">
                                <?= htmlspecialchars($error) ?>
                            </div>
                        <?php endif; ?>
                        
                        <form method="POST" action="">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Contraseña</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100 mb-3">Ingresar</button>
                            <a href="usuarios/crear.php" class="btn btn-outline-secondary w-100">Registrarse</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
