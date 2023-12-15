<?php
session_start();

// Verifica si el usuario ha iniciado sesión
if (isset($_SESSION['username'])) {
    $nombreDeUsuario = $_SESSION['username'];
} else {
    $nombreDeUsuario = null;
}
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
    <link rel = "stylesheet" href = "/lyons.peru/hombres/camisetas/stylecam.css">
</head>
<body>
    
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
                        echo '<li class="nav-item px-2 py-2"><span class="nav-link text-uppercase text-dark">Hola, ' . $nombreDeUsuario . '</span></li>';
                        echo '<li class="nav-item px-2 py-2"><a class="nav-link text-uppercase text-dark" href="/lyons.peru/logout.php">CERRAR SESIÓN</a></li>';
                    } else {
                        // Usuario no logeado
                        echo '<li class="nav-item px-2 py-2"><a class="nav-link text-uppercase text-dark" href="/lyons.peru/index.html#login">INICIAR SESIÓN</a></li>';
                    }
                    ?>
                    <li class="nav-item px-2 py-2">
                    <a class="nav-link text-uppercase text-dark" href="/LYONS.PERU/hombres/hombres.php">CAMISETAS</a>
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
        <img
            src="/lyons.peru/images/guantes.jpg"
            alt="imagen-producto"
        />
    </div>
    <div class="container-info-product">
        <div class="container-price">
            <span>Nike Men's Fundamental Training</span>
        </div>

        <div class="">
            <div class="">
                S/79.99</div>
        </div>

        <div class="container-details-product">
            
            <div class="form-group">
                <label for="size">Talla</label>
                <select name="size" id="size">
                    <option disabled selected value="">
                        Escoge una opción
                    </option>
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
            <div class="product" data-product-name="Nike Men's Fundamental Training" data-product-price="79.99">
                <button class="btn-add-to-cart milan">Añadir al carrito</button>
            </div>
        </div>

        <div class="container-description">
            <div class="title-description">
                <h4>Descripción</h4>
            </div>
            <div class="text-description">
                <p>
                    Los Essential Fitness son unos guantes Nike para entrenar con seguridad y soporte. Añade una palma acolchada y con perforaciones para una ventilación estratégica durante tu rutina.En la muñeca, una correa de velcro te dará la sujeción ideal.
                </p>
            </div>
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
<script src="/lyons.peru/hombres/camisetas/index.js"></script>
</body>
</html>

<!-- footer -->