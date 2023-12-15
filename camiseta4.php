<?php
session_start();
include('conexion.php');

if (isset($_SESSION['username'])) {
    $nombreDeUsuario = $_SESSION['username'];
} else {
    $nombreDeUsuario = null;
}

// Establece la conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ecommerce_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Error de conexión a la base de datos: " . $conn->connect_error);
}

// Consulta para obtener la información del producto desde la base de datos
$idProducto = 4; // Cambia el ID según corresponda
$sql = "SELECT nombre, precio, descripcion, stock FROM productos WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $idProducto);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $nombreProducto = $row["nombre"];
    $precioProducto = $row["precio"];
    $descripcionProducto = $row["descripcion"];
    $stockProducto = $row["stock"];

    // Mostrar información del producto, si es necesario

    $stmt->close();
} else {
    echo "No se encontró el producto.";
}

// Procesar la compra y actualizar el stock al presionar "Listo para Pagar"
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['pay_button'])) {
    // Obtener el carrito del usuario desde la sesión
    $cartProducts = $_SESSION['cart'];

    // Verificar si hay suficiente stock disponible para todos los productos en el carrito
    $insufficientStock = false;

    foreach ($cartProducts as $cartProduct) {
        $productId = $cartProduct['id'];
        $quantity = $cartProduct['quantity'];

        // Verificar si hay suficiente stock
        if ($quantity > $stockProducto) {
            $insufficientStock = true;
            break;
        }
    }

    if (!$insufficientStock) {
        // Procesar la compra y actualizar el stock para cada producto en el carrito
        foreach ($cartProducts as $cartProduct) {
            $productId = $cartProduct['id'];
            $quantity = $cartProduct['quantity'];

            $nuevoStock = $stockProducto - $quantity;
            $sqlUpdate = "UPDATE productos SET stock = ? WHERE id = ?";
            $stmt = $conn->prepare($sqlUpdate);

            if (!$stmt) {
                die("Error en la preparación de la consulta: " . $conn->error);
            }

            $stmt->bind_param("ii", $nuevoStock, $productId);
            $stmt->execute();

            if ($stmt->affected_rows <= 0) {
                // Fallo al actualizar el stock
                echo "Error al actualizar el stock para el producto con ID $productId.";
                $stmt->close();
                exit();
            }

            $stmt->close();
        }

        // Éxito al actualizar el stock para todos los productos en el carrito
        echo "Stock actualizado en la base de datos.";

        // Puedes realizar acciones adicionales aquí, como agregar el producto al historial de compras del usuario, etc.

        // Limpiar el carrito del usuario después de la compra
        $_SESSION['cart'] = [];

        // Redireccionar al usuario al checkout.php después de 3 horas
        $tiempoEspera = 3 * 60 * 60; // 3 horas en segundos
        $tiempoExpiracion = time() + $tiempoEspera;
        setcookie("idProducto", $idProducto, $tiempoExpiracion, "/lyons.peru/pago/checkout.php");
    } else {
        // Mostrar un mensaje de error si no hay suficiente stock para algún producto en el carrito
        echo "No hay suficiente stock disponible para algunos productos en el carrito.";
    }
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tienda</title>
    <!-- fontawesome cdn -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-1ycn6IcaQQ40/MKBW2W4Rhis/DbILU74C1vSrLJxCq57o941Ym01SwNsOMqvEBFlcgUa6xLiPY/NS5R+E6ztJQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- bootstrap css -->
    <link rel = "stylesheet" href = "bootstrap-5.0.2-dist/css/bootstrap.min.css">
    <!-- css -->
    <link rel = "stylesheet" href = "stylecam.css">
</head>
<body>
     <!-- barra superior -->
   <!-- barra superior -->
   <nav class="navbar navbar-expand-lg navbar-light bg-white py-4 fixed-top">
        <div class="container">
            <a class="navbar-brand d-flex justify-content-between align-items-center order-lg-0" href="/lyons.peru/index.html">
                <img src="/lyons.peru/images/e6a22d40d754eedbafa15bb2df4ef84b.png" alt="site icon">
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
                        echo '<li class="nav-item px-2 py-2"><a class="nav-link text-uppercase text-dark" href="/lyons.peru/logout.php">CERRAR SESIÓN</a></li>';
                    } else {
                        // Usuario no logeado
                        echo '<li class="nav-item px-2 py-2"><a class="nav-link text-uppercase text-dark" href="/lyons.peru/index.html#login">INICIAR SESIÓN</a></li>';
                    }
                    ?>
                    <li class="nav-item px-2 py-2">
                        <a class="nav-link text-uppercase text-dark" href="../hombres.php">CAMISETAS</a>
                    </li>
                    <li class="nav-item px-2 py-2">
                        <a class="nav-link text-uppercase text-dark" href="/lyons.peru/articulos/articulos.php">ARTICULOS DEPORTIVOS</a>
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
                <!-- Los elementos del carrito se agregarán aquí dinámicamente -->
            </ul>
            <div class="cart-total">
                Total: S/<span id="cart-total">0.00</span>
            </div>
            <div class="cart-buttons">
            <a href="/lyons.peru/pago/checkout.php"><button class="pay-button">Listo para Pagar!</button></a>
                <button class="clear-button">Limpiar</button>
            </div>
        </div>
    </nav>

    <main>
        <div class="container-img">
            <img src="/lyons.peru/images/perucamiseta.jpg" alt="imagen-producto" />
        </div>
        <div class="container-info-product">
            <div class="container-price">
                <span><?php echo $nombreProducto; ?></span>
            </div>

            <div class="price">
                <div class="">
                    S/<?php echo $precioProducto; ?>
                </div>
            </div>

            <div class="container-stock">
                <p>Stock Disponible: <span id="stock"><?php echo $stockProducto; ?></span></p>
            </div>


            <div class="container-details-product">
                <div class="form-group">
                    <label for="size">Talla</label>
                    <select name="size" id="size">
                        <option value="40">S</option>
                        <option value="42">M</option>
                        <option value="43">L</option>
                        <option value="44">XL</option>
                    </select>
                </div>
            </div>

            <div class="container-add-cart">
                <div class="container-quantity">
                    <input
                        type="number"
                        placeholder="1"
                        value="1"
                        min="1"
                        class="input-quantity"
                    />
                    <div class="btn-increment-decrement">
                        <i class="fa-solid fa-chevron-up" id="increment"></i>
                        <i class="fa-solid fa-chevron-down" id="decrement"></i>
                    </div>
                </div>
                <div class="product" data-product-name="<?php echo $nombreProducto; ?>" data-product-price="<?php echo $precioProducto; ?>">
                    <button class="btn-add-to-cart milan">Añadir al carrito</button>
                </div>
            </div>

            <div class="container-description">
                <div class="title-description">
                    <h4>Descripción</h4>
                    
                </div>
                <div class="text-description">
                    <p>
                        <?php echo $descripcionProducto; ?>
                    </p>
                </div>
                <a class="dc-link" href="https://api.whatsapp.com/send?phone=51962058509&amp;text=Hola%21%20Vengo%20de%20la%20página%20web%2C%20quisiera%20saber%20información%20de%20la%20camiseta%3A" target="_blank" style="text-decoration: none;">
                    <span>
                        <div class="what-1 button success lowercase reveal-icon whatsapp-link" style="border-radius: 1009px; background: #29A71A; color: #fff; padding: 10px; text-align: center; font-weight: 500; font-size: 18px; width: 99%; font-weight: 700;">
                            <i class="icon-checkmark"></i>
                            <span>Escríbenos al WhatsApp!</span>
                        </div>
                    </span>
                </a>


            </div>
        </div>
    </main>

    
<footer class = "bg-dark py-5">
        <div class = "container">
            <div class = "row text-white g-4">
                <div class = "col-md-6 col-lg-3">
                <a class = "text-uppercase text-decoration-none brand text-white" href = "/lyons.peru/index.html">LYONS PERU</a>
                </div>


                <div class = "col-md-6 col-lg-3">
                    <h5 class = "fw-light mb-3">Visitanos</h5>
                    <div class = "d-flex justify-content-start align-items-start my-2 text-muted">
                        <span class = "me-3">
                            <i class = "fas fa-envelope"></i>
                        </span>
                        <span class = "fw-light">
                            @camisetas.lyonsperu
                        </span>
                    </div>
                    <div class = "d-flex justify-content-start align-items-start my-2 text-muted">
                        <span class = "me-3">
                            <i class = "fas fa-phone-alt"></i>
                        </span>
                        <span class = "fw-light">
                            962058509
                        </span>
                    </div>
                </div>

                <div class = "col-md-6 col-lg-3">
                    <h5 class = "fw-light mb-3">Follow Us</h5>
                    <div>
                        <ul class = "list-unstyled d-flex">
                            <li>
                                <a href = "https://web.facebook.com/p/Camisetas-Lyons-Per%C3%BA-100086170234616/?paipv=0&eav=AfZBc7afEmNTeT-i8LepYbvjOXOvhqjrrmlmP7Bggn4SdF7e0C_g0Neb-PT6j3BnWMs&_rdc=1&_rdr" class = "text-white text-decoration-none text-muted fs-4 me-4">
                                    <i class = "fab fa-facebook-f"></i>
                                </a>
                            </li>
                            <li>
                                <a href = "https://www.instagram.com/camisetas.lyonsperu/" class = "text-white text-decoration-none text-muted fs-4 me-4">
                                    <i class = "fab fa-instagram"></i>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!-- end of footer -->
    <script
    src="https://kit.fontawesome.com/81581fb069.js"
    crossorigin="anonymous"
></script>
    <script src="../camisetas/index.js"></script>
</body>
</html>

<!-- footer -->