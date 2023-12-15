<?php
// Incluye la configuración de la conexión a la base de datos
include('conexion.php');

// Verifica si se enviaron los datos de usuario y contraseña
if (isset($_POST['username']) && isset($_POST['password'])) {
    $correo = $_POST['username']; // Cambia "username" a "correo" para reflejar el campo de correo electrónico
    $password = $_POST['password'];

    // Evita la inyección SQL utilizando declaraciones preparadas
    $sql = "SELECT * FROM usuarios WHERE correo = ? AND contrasena = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("ss", $correo, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        // Usuario autenticado
        $row = $result->fetch_assoc();
        $rol = $row['rol']; // Obtén el rol del usuario
        $nombreDeUsuario = $row['nombre_de_usuario']; // Obtén el nombre de usuario desde la base de datos

        // Inicia una sesión (si es necesario)
        session_start();

        // Establece el nombre de usuario en la variable de sesión
        $_SESSION['username'] = $nombreDeUsuario;

        if ($rol === 'administrador') {
            // Redirige al usuario a la página de administrador
            header("Location: /lyons.peru/admin_panel/index.php");
            exit();
        } else {
            // Redirige al usuario a la página de productos
            header("Location: /lyons.peru/hombres/hombres.php");
            exit();
        }
    } else {
        // Autenticación fallida
        // Puedes mostrar un mensaje de error o redirigir nuevamente a la página de inicio de sesión con un mensaje de error
        header("Location: index.html?error=1");
        exit();
    }
}
?>
