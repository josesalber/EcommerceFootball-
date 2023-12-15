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
                        echo '<li class="nav-item px-2 py-2"><a span class="nav-link text-uppercase text-dark" href="../hombres/perfil.php">Hola, ' . $nombreDeUsuario . '</span></li>';
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
                    <li class="nav-item px-2 py-2">
                        <a class="nav-link text-uppercase text-dark" href="#about">CONOCENOS</a>
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


    <!-- Productos -->
    <section id = "collection" class = "py-5">
        <div class = "container">
            <div class = "title text-center">
                <h2 class = "position-relative d-inline-block">MAS BUSCADOS!</h2>
            </div>
            <div id="float-whatsapp" style="position: fixed; bottom: 40px; right: 40px;">
                <a href="https://wa.me/51962058509?text=Hola%21+Vengo+de+la+página+web%2C+quisiera+saber+información+de+la+camiseta%3A" target="_blank">
                    <img src="https://golmasport.com/by_studiobluna_2017/wp-content/themes/golmas_des/img/whatsapp-golmas.png" width="60" height="60">
                </a>
            </div>

            <div class = "row g-0">
                

                <div class = "collection-list mt-4 row gx-0 gy-3">
                    <div class = "col-md-6 col-lg-4 col-xl-3 p-2 best">
                        
                        <div class = "collection-img position-relative">
                            <a href="../hombres/camisetas/camiseta1.php"> <img src = "../images/milan.jpg" class = "w-100"></a>
                            
                        </div>
                        <div class = "text-center">
                            <p class = "text-capitalize my-1">Camiseta Milan 2023</p>
                            <span class = "fw-bold">S/199.99 </span>
                        </div>
                    </div>

                    <div class = "col-md-6 col-lg-4 col-xl-3 p-2 feat">
                        <div class = "collection-img position-relative">
                            <a href="../hombres/camisetas/camiseta2.php"> <img src = "../images/alianza.jpg" class = "w-100"></a>
                        </div>
                        <div class = "text-center">
                            <p class = "text-capitalize my-1">Camiseta Alianza Lima Alterna</p>
                            <span class = "fw-bold">S/162.99 </span>
                        </div>
                    </div>

                    <div class = "col-md-6 col-lg-4 col-xl-3 p-2 new">
                        <div class = "collection-img position-relative">
                            <a href="../hombres/camisetas/camiseta3.php"><img src = "../images/juve.jpg" class = "w-100"></a>
                        </div>
                        <div class = "text-center">

                            <p class = "text-capitalize my-1">Juventus 2023</p>
                            <span class = "fw-bold">S/159.99</span>
                        </div>
                    </div>

                    <div class = "col-md-6 col-lg-4 col-xl-3 p-2 best">
                        <div class = "collection-img position-relative">
                            <a href="../hombres/camisetas/camiseta4.php"><img src = "../images/perucamiseta.jpg" class = "w-100"></a>
                        </div>
                        <div class = "text-center">

                            <p class = "text-capitalize my-1">Camiseta Peru 2023</p>
                            <span class = "fw-bold">S/129.99</span>
                        </div>
                    </div>

                    <div class = "col-md-6 col-lg-4 col-xl-3 p-2 feat">
                        <div class = "collection-img position-relative">
                            <a href="../hombres/camisetas/camiseta5.php"><img src = "../images/perucam.jpg" class = "w-100"></a>
                        </div>
                        <div class = "text-center">

                            <p class = "text-capitalize my-1">Camiseta Local Peru</p>
                            <span class = "fw-bold">S/129.99 </span>
                        </div>
                    </div>

                    <div class = "col-md-6 col-lg-4 col-xl-3 p-2 new">
                        <div class = "collection-img position-relative">
                            <a href="../hombres/camisetas/camiseta6.php"><img src = "../images/boys.jpg" class = "w-100"></a>
                        </div>
                        <div class = "text-center">
                            
                            <p class = "text-capitalize my-1">Camiseta Sport Boys 2023</p>
                            <span class = "fw-bold">S/89.99</span>
                        </div>
                    </div>

                    <div class = "col-md-6 col-lg-4 col-xl-3 p-2 best">
                        <div class = "collection-img position-relative">
                            <a href="../hombres/camisetas/camiseta7.php"><img src = "../images/barca.jpg" class = "w-100"></a>
                        </div>
                        <div class = "text-center">

                            <p class = "text-capitalize my-1">Camiseta Barcelona 2023</p>
                            <span class = "fw-bold">S/279.99</span>
                        </div>
                    </div>

                    <div class = "col-md-6 col-lg-4 col-xl-3 p-2 feat">
                        <div class = "collection-img position-relative">
                            <a href="../hombres/camisetas/camiseta8.php"><img src = "../images/u.jpg" class = "w-100"></a>
                        </div>
                        <div class = "text-center">
                            <p class = "text-capitalize my-1">Camiseta Universitario 2023</p>
                            <span class = "fw-bold">S/138.99</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>



    
    <!-- fin Productos -->

  
    <!-- Acerca Nosotros -->
    <section id = "about" class = "py-5">
        <div class = "container">
            <div class = "row gy-lg-5 align-items-center">
                <div class = "col-lg-6 order-lg-1 text-center text-lg-start">
                    <div class = "title pt-3 pb-5">
                        <h2 class = "position-relative d-inline-block ms-4">LYONS PERU</h2>
                    </div>
                    <p class = "lead text-muted">En Lyons Peru, somos apasionados por la moda y la autenticidad. Nos especializamos en ofrecer camisetas de temporada y colección que reflejen las últimas tendencias de la moda, sin comprometer la calidad ni la comodidad. Nuestra misión es ayudarte a expresar tu estilo único y personalidad a través de prendas de alta calidad, diseñadas con esmero y dedicación. Valoramos a nuestros clientes y trabajamos constantemente para brindar una experiencia de compra excepcional en línea. ¡Gracias por vistarnos!</p>
                </div>
                <div class = "col-lg-6 order-lg-0">
                    <img src = "../images/logotienda.png" alt = "" class = "img-fluid">
                </div>
            </div>
        </div>
    </section>
    <!-- fin Acerca Nosotros -->



    <!-- footer -->
    <footer class = "bg-dark py-5">
        <div class = "container">
            <div class = "row text-white g-4">
                <div class = "col-md-6 col-lg-3">
                    <a class = "text-uppercase text-decoration-none brand text-white" href = "../index.html">LYONS PERU</a>
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
                    <h5 class = "fw-light mb-3">Siguenos</h5>
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


   <!-- bootstrap js -->
   <script src = "bootstrap-5.0.2-dist/js/bootstrap.min.js"></script>
   <script src="https://kit.fontawesome.com/81581fb069.js" crossorigin="anonymous"></script>
   <script src="/LYONS.PERU/hombres/camisetas/index.js"></script>
    



  
</body>
</html>
