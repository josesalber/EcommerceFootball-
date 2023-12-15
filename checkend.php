<?php
session_start();

// Verifica si el usuario ha iniciado sesión
if (isset($_SESSION['username'])) {
    $nombreDeUsuario = $_SESSION['username'];
} else {
    $nombreDeUsuario = null;
}
?><!DOCTYPE html>
<html lang="en">
<head>
    <!-- ... (encabezado) ... -->
</head>
<body>
    <div class="container">
        <h1>¡Gracias por tu compra!</h1>
        <p>Tu pedido ha sido procesado con éxito.</p>
        <!-- ... (otros detalles o enlaces a seguir comprando) ... -->
    </div>
    <!-- ... (pie de página) ... -->
</body>
</html>
