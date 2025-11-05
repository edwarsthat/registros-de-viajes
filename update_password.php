<?php
/**
 * Script para actualizar la contraseña del usuario admin con hash seguro
 */

require_once 'db.php';

// Contraseña en texto plano
$password_plano = '123456';

// Generar hash seguro con bcrypt
$password_hash = password_hash($password_plano, PASSWORD_DEFAULT);

// Actualizar la contraseña en la base de datos
$stmt = $pdo->prepare("UPDATE usuario SET password = ? WHERE email = 'admin@test.com'");
$stmt->execute([$password_hash]);

echo "Contraseña actualizada exitosamente con hash seguro.\n";
echo "Email: admin@test.com\n";
echo "Password: 123456\n";
echo "Hash generado: " . $password_hash . "\n";
?>
