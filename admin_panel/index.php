<?php
session_start();
require_once('../conexion.php');

if (isset($_SESSION['username'])) {
    $nombreDeUsuario = $_SESSION['username'];

    // Obtener datos de usuarios desde la base de datos
    $queryUsuarios = "SELECT * FROM usuarios";
    $resultUsuarios = mysqli_query($conexion, $queryUsuarios);

    // Obtener datos de productos desde la base de datos
    $queryProductos = "SELECT * FROM productos";
    $resultProductos = mysqli_query($conexion, $queryProductos);
} else {
    $nombreDeUsuario = null;
}

// Asegúrate de cerrar la conexión a la base de datos al finalizar
mysqli_close($conexion);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard de Administrador</title>
    <link rel="stylesheet" href="estilos.css"> <!-- Agrega tus estilos CSS personalizados -->
    <link rel="stylesheet" href="styleadmin.css">

</head>
<body>
    <header>
        <h1>Hola, <?php echo $nombreDeUsuario; ?></h1>
        <a class="nav-link logout-button" href="../logout.php">CERRAR SESION</a>
        <a href="/lyons.peru/admin_panel/listapedidos.php" class="visit-website-button">PEDIDOS</a>
        <a href="/lyons.peru/admin_panel/index.php" class="visit-website-button">USUARIOS</a>
        <a href="../hombres/hombres.php" class="visit-website-button">TIENDA</a>

    </header>

    <section class="usuarios">
        <h2>Usuarios</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Nombre de Usuario</th>
                <th>Correo</th>
                <th>Rol</th>
            </tr>
            <?php
            while ($usuario = mysqli_fetch_assoc($resultUsuarios)) {
                echo "<tr>";
                echo "<td>" . $usuario['id'] . "</td>";
                echo "<td>" . $usuario['nombre_de_usuario'] . "</td>";
                echo "<td>" . $usuario['correo'] . "</td>";
                echo "<td>" . $usuario['rol'] . "</td>";
                echo "</tr>";
            }
            ?>
        </table>
    </section>

    <section class="productos">
        <h2>Productos</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Precio</th>
                <th>Descripción</th>
            </tr>
            <?php
            while ($producto = mysqli_fetch_assoc($resultProductos)) {
                echo "<tr>";
                echo "<td>" . $producto['id'] . "</td>";
                echo "<td>" . $producto['nombre'] . "</td>";
                echo "<td>" . $producto['precio'] . "</td>";
                echo "<td>" . $producto['descripcion'] . "</td>";
                echo "</tr>";
            }
            ?>
        </table>
    </section>
    
</body>
</html>
