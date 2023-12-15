<?php
// Incluir el archivo de conexión a la base de datos
require_once '../conexion.php';

// Verificar si el usuario está autenticado
session_start();
if (!isset($_SESSION['username'])) {
    // Redirigir al usuario a la página de inicio de sesión si no está autenticado
    header("Location: /lyons.peru/login.php");
    exit();
}

// Obtener el nombre de usuario desde la sesión
$nombreDeUsuario = $_SESSION['username'];

// Verificar si el usuario es un administrador (debes implementar tu propio sistema de autenticación aquí)
$is_admin = true; // Debes implementar la lógica adecuada para verificar si el usuario es administrador

if (!$is_admin) {
    // Redirigir a la página de inicio u otra página adecuada si no es un administrador
    header("Location: /lyons.peru/index.php");
    exit();
}

// Actualizar el estado del pedido si se envió un formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_pedido']) && isset($_POST['nuevo_estado'])) {
    $idPedido = $_POST['id_pedido'];
    $nuevoEstado = $_POST['nuevo_estado'];

    // Actualizar el estado del pedido en la base de datos
    $conn = new mysqli($host, $usuario, $contrasena, $base_de_datos);

    if ($conn->connect_error) {
        die("Error en la conexión a la base de datos: " . $conn->connect_error);
    }

    $updateQuery = "UPDATE pedidos SET estado = ? WHERE id = ?";
    $stmt = $conn->prepare($updateQuery);

    if (!$stmt) {
        die("Error en la preparación de la consulta de actualización: " . $conn->error);
    }

    $stmt->bind_param("si", $nuevoEstado, $idPedido);
    $stmt->execute();

    $stmt->close();
    $conn->close();
}

// Consultar la tabla de pedidos
$conn = new mysqli($host, $usuario, $contrasena, $base_de_datos);

if ($conn->connect_error) {
    die("Error en la conexión a la base de datos: " . $conn->connect_error);
}

$result = $conn->query("SELECT * FROM pedidos");

// Cerrar la conexión a la base de datos
$conn->close();
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

    <div class="container">
        <h1>Lista de Pedidos</h1>
        <table class="table">
            <thead>
                <tr>
                    <th>ID de Pedido</th>
                    <th>Nombre de Usuario</th>
                    <th>Correo</th>
                    <th>Total</th>
                    <th>Estado</th>
                    <th>Lista de Productos</th> 

                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['id'] . "</td>";
                        echo "<td>" . $row['nombre_usuario'] . "</td>";
                        echo "<td>" . $row['correo'] . "</td>";
                        echo "<td>" . $row['precio_total'] . "</td>";
                        echo "<td>";
                        // Formulario en línea para actualizar el estado del pedido
                        echo "<form method='post' action='listapedidos.php'>";
                        echo "<input type='hidden' name='id_pedido' value='" . $row['id'] . "'>";
                        echo "<select name='nuevo_estado'>";
                        echo "<option value='En Proceso'" . ($row['estado'] == 'En Proceso' ? ' selected' : '') . ">En Proceso</option>";
                        echo "<option value='Enviado'" . ($row['estado'] == 'Enviado' ? ' selected' : '') . ">Enviado</option>";
                        echo "<option value='Entregado'" . ($row['estado'] == 'Entregado' ? ' selected' : '') . ">Entregado</option>";
                        echo "</select>";
                        echo "<button type='submit'>Actualizar</button>";
                        echo "</form>";
                        echo "</td>";
                        echo "<td>" . $row['lista_productos'] . "</td>";

                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>No hay pedidos disponibles.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <footer class="bg-dark py-5">
        <div class="container">
            <!-- Pie de página ... -->
        </div>
    </footer>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="bootstrap-5.0.2-dist/js/bootstrap.min.js"></script>
</body>
</html>
