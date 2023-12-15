<?php
$host = "localhost"; // Cambia a la dirección del servidor de tu base de datos
$usuario = "root"; // Cambia al nombre de usuario de tu base de datos
$contrasena = ""; // Cambia a la contraseña de tu base de datos
$base_de_datos = "ecommerce_db"; // Cambia al nombre de tu base de datos

// Crear una conexión a la base de datos
$conexion = new mysqli($host, $usuario, $contrasena, $base_de_datos);

// Verificar la conexión
if ($conexion->connect_error) {
    die("Error de conexión a la base de datos: " . $conexion->connect_error);
}

// Establecer la codificación de caracteres a UTF-8 (opcional)
$conexion->set_charset("utf8");

// A partir de este punto, la variable $conexion contiene la conexión a la base de datos
?>

