<?php
session_start();

// Limpia todas las variables de sesión
$_SESSION = array();

// Destruye la sesión
session_destroy();

// Agregar un mensaje de depuración
echo "Sesión cerrada correctamente";

// Redirige al usuario a la página de inicio o a donde desees
header("Location: ../lyons.peru/hombres/hombres.php");
exit();
?>
