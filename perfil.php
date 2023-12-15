<?php
session_start();

// Verifica si el usuario ha iniciado sesión
if (isset($_SESSION['username'])) {
    $nombreDeUsuario = $_SESSION['username'];

    // Accede a la base de datos y obtén la información del usuario
    include('../conexion.php');
    $query = "SELECT * FROM usuarios WHERE nombre_de_usuario = '$nombreDeUsuario'";
    $result = $conexion->query($query);

    if ($result->num_rows > 0) {
        $usuario = $result->fetch_assoc();
        $esAdmin = $usuario['rol'] === 'administrador';

        // Verifica si el usuario es cliente
        $esCliente = isset($usuario['rol']) && $usuario['rol'] === 'cliente';
    }
} else {
    $nombreDeUsuario = null;
    $esCliente = false; // Inicializa la variable para evitar problemas
}

// Cierra la conexión a la base de datos
$conexion->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CAMISETAS</title>
    <!-- fontawesome cdn -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-1ycn6IcaQQ40/MKBW2W4Rhis/DbILU74C1vSrLJxCq57o941Ym01SwNsOMqvEBFlcgUa6xLiPY/NS5R+E6ztJQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- bootstrap css -->
    <link rel = "stylesheet" href = "bootstrap-5.0.2-dist/css/bootstrap.min.css">
    <!-- css -->
    <link rel = "stylesheet" href = "stylett.css">
</head>
<body>
    
    <!-- barra superior -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white py-4 fixed-top">
        <div class="container">
            <a class="navbar-brand d-flex justify-content-between align-items-center order-lg-0" href="../index.html">
                <img src="../images/e6a22d40d754eedbafa15bb2df4ef84b.png" alt="site icon">
                <span class="text-uppercase fw-lighter ms-2">LYONS.PERU</span>
            </a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse order-lg-1" id="navMenu">
                <ul class="navbar-nav mx-auto text-center">
                <?php
                    if (isset($nombreDeUsuario)) {
                        // Usuario logeado
                        echo '<li class="nav-item px-2 py-2"><a span class="nav-link text-uppercase text-dark" href="/lyons.peru/hombres/perfil.php">Hola, ' . $nombreDeUsuario . '</span></li>';
                        echo '<li class="nav-item px-2 py-2"><a class="nav-link text-uppercase text-dark" href="../logout.php">CERRAR SESIÓN</a></li>';
                    } else {
                        // Usuario no logeado
                        echo '<li class="nav-item px-2 py-2"><a class="nav-link text-uppercase text-dark" href="../index.html#login">INICIAR SESIÓN</a></li>';
                    }
                    ?>
                    <li class="nav-item px-2 py-2">
                        <a class="nav-link text-uppercase text-dark" href="../hombres/hombres.php">CAMISETAS</a>
                    </li>
                    <li class="nav-item px-2 py-2">
                        <a class="nav-link text-uppercase text-dark" href="../articulos/articulos.php">ARTICULOS DEPORTIVOS</a>
                    </li>
                    
                    
                </ul>
            </div>
        </div>
        <div class="cart-icon">
            <i class="fas fa-shopping-cart"></i>
            <span class="cart-count">0</span>
        </div>
    
        <div class="cart">
            <button class="close-cart">X</button>
            <h2>Carrito de Compras</h2>
            <ul class="cart-items">
            </ul>
            <div class="cart-total">
                Total: S/<span id="cart-total">0.00</span>
            </div>
            <div class="cart-buttons">
                <a href="/lyons.peru/pago/checkout.php"><button class="pay-button">Pagar</button></a>
                <button class="clear-button">Limpiar</button>
            </div>
        </div>
    </nav>

    
    <!-- fin barra superior -->


    <section id="perfil" class="py-5">
    <div class="container">
    <div class="row">
            <div class="col-md-6 order-2 order-md-1">
                <?php
                if (isset($nombreDeUsuario)) {
                    echo '<h2>Hola, ' . $usuario['nombre_de_usuario'] . '</h2>';
                    echo '<p>Correo Electrónico: ' . $usuario['correo'] . '</p>';
                    echo '<p>Dirección: ' . $usuario['direccion'] . '</p>';
                    echo '<p>Teléfono: ' . $usuario['telefono'] . '</p>';

                    // Verifica si el usuario es administrador
                    if ($esAdmin) {
                        // Agrega el enlace al dashboard solo si es administrador
                        echo '<p><a href="/lyons.peru/admin_panel/index.php" class="text-decoration-none" style="color: rgb(135, 189, 191);">IR A PANEL ADMIN</a></p>';
                    }
                    // Verifica si el usuario es cliente
                    $esCliente = isset($usuario['rol']) && $usuario['rol'] === 'cliente';

                    if ($esCliente) {
                        // Muestra la información del pedido
                        echo '<h3 style="color: rgb(135, 189, 191)";>Información del Pedido</h3>';
                        
                        // Accede a la base de datos y obtén la información del último pedido "En Proceso" del cliente
                        include('../conexion.php');
                        $pedidoQuery = "SELECT * FROM pedidos WHERE nombre_usuario = '$nombreDeUsuario' AND estado = 'En Proceso' ORDER BY id DESC LIMIT 1";
                        $pedidoResult = $conexion->query($pedidoQuery);
                    
                        if ($pedidoResult->num_rows > 0) {
                            $pedido = $pedidoResult->fetch_assoc();
                    
                            echo '<p>ID del Pedido: ' . $pedido['id'] . '</p>';
                            echo '<p>Precio Total: S/ ' . number_format($pedido['precio_total'], 2) . '</p>';
                            echo '<p>Estado del Pedido: ' . $pedido['estado'] . '</p>';
                            echo '<p>Direccion de Entrega: ' . $pedido['direccion'] . '</p>';
                            echo '<p>Tu Pedido: ' . $pedido['lista_productos'] . '</p>';

                        } else {
                            echo '<p>No tienes pedidos pendientes.</p>';
                        }
                        echo '<h3 style="color: rgb(135, 189, 191)">Todos tus pedidos entregados</h3>';
                        $pedidosEntregadosQuery = "SELECT * FROM pedidos WHERE nombre_usuario = '$nombreDeUsuario' AND estado = 'Entregado'";
                        $pedidosEntregadosResult = $conexion->query($pedidosEntregadosQuery);
                    
                        if ($pedidosEntregadosResult->num_rows > 0) {
                            while ($pedidoEntregado = $pedidosEntregadosResult->fetch_assoc()) {
                                echo '<p>ID del Pedido: ' . $pedidoEntregado['id'] . '</p>';
                                echo '<p>Precio Total: S/ ' . number_format($pedidoEntregado['precio_total'], 2) . '</p>';
                                echo '<p>Estado del Pedido: ' . $pedidoEntregado['estado'] . '</p>';
                                echo '<p>Direccion de Entrega: ' . $pedidoEntregado['direccion'] . '</p>';
                                echo '<hr>'; // Línea divisoria entre pedidos
                            }
                        } else {
                                echo '<p>No haz realizado pedidos hasta el momento.</p>';
                        }
                        // Cierra la conexión a la base de datos
                        $conexion->close();
                    }
                } else {
                    echo '<h2>Perfil de Usuario</h2>';
                    echo '<p>No has iniciado sesión.</p>';
                }
                ?>
            </div>
            <div class="col-md-6 order-1 order-md-2">
                <!-- Agrega la imagen aquí -->
                <img src="../images/perfilimg.jpg" alt="Imagen de perfil" style="width: 100%; max-width: 2000px;">
            </div>
        </div>
    </div>
</section>
<div id="float-whatsapp" style="position: fixed; bottom: 40px; right: 40px;">
    <?php
    // Verifica si el pedido está definido
    if (isset($pedido['id'])) {
        // Incluye la ID del pedido en el enlace de WhatsApp
        $whatsappLink = 'https://wa.me/51962058509?text=Hola!%20Necesito%20ayuda%20con%20mi%20pedido%20ID:%20' . $pedido['id'];
    } else {
        // Enlace de WhatsApp genérico si la ID del pedido no está disponible
        $whatsappLink = 'https://wa.me/51962058509?text=Hola!%20Necesito%20ayuda%20con%20mi%20pedido.';
    }
    ?>
    <a href="<?php echo $whatsappLink; ?>" target="_blank">
        <img src="https://golmasport.com/by_studiobluna_2017/wp-content/themes/golmas_des/img/whatsapp-golmas.png" width="60" height="60">
    </a>
</div>





    

  



    <!-- footer -->
    
   <!-- bootstrap js -->
   <script src = "bootstrap-5.0.2-dist/js/bootstrap.min.js"></script>
   <script src="https://kit.fontawesome.com/81581fb069.js" crossorigin="anonymous"></script>
   <script src="/LYONS.PERU/hombres/camisetas/index.js"></script>
    



  
</body>
</html>
/