<?php
/**
 * Cerrar sesión
 * 
 * Destruye la sesión del usuario y redirige al login
 */

session_start();
session_destroy();
header('Location: index.php');
exit;
?>