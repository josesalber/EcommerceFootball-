<?php
include('conexion.php');

if (isset($_POST['new_username']) && isset($_POST['new_password']) && isset($_POST['new_email']) && isset($_POST['new_address']) && isset($_POST['new_phone'])) {
    $new_username = $_POST['new_username'];
    $new_password = $_POST['new_password'];
    $new_email = $_POST['new_email'];
    $new_address = $_POST['new_address'];
    $new_phone = $_POST['new_phone'];

    // Realiza la inserción de los datos en la base de datos
    $sql = "INSERT INTO usuarios (nombre_de_usuario, contrasena, correo, direccion, telefono, rol) VALUES ('$new_username', '$new_password', '$new_email', '$new_address', '$new_phone', 'cliente')";

    if ($conexion->query($sql) === TRUE) {
        // Registro exitoso, redirige al usuario a la página de inicio de sesión (en este caso, a la misma página "index.html")
        header("Location: index.html");
        exit();
    } else {
        // Error en el registro, puedes mostrar un mensaje de error en "index.html" o redirigir nuevamente a la misma página
        header("Location: index.html?error=1");
        exit();
    }
}
?>
