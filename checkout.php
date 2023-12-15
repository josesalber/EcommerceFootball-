<?php
session_start();

$precioTotal = 0;

// Obtiene el carrito de productos desde el almacenamiento local (localStorage)

$items = array();

if (!empty($cartProducts)) {
    foreach ($cartProducts as $product) {
        $item = array(
            'title' => $product['productName'], // Nombre del producto
            'quantity' => $product['quantity'], // Cantidad
            'currency_id' => 'PEN', // Moneda (por ejemplo, PEN para soles peruanos)
            'unit_price' => $product['price'], // Precio unitario
        );
        $items[] = $item;
        $precioTotal += $item['quantity'] * $item['unit_price']; // Calcula el precio total
    }
}

// Eliminamos cualquier referencia a MercadoPago

if (!isset($_SESSION['username'])) {
    // El usuario no ha iniciado sesión, redirige a la página de inicio de sesión
    header("Location: http://localhost:8080/lyons.peru/index.html#login");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LYONS.PERU</title>
    <link rel="stylesheet" href="/lyons.peru/pago/stylepago.css">
    <link rel="stylesheet" href="bootstrap-5.0.2-dist/css/bootstrap.min.css">

</head>
<body>
    <div class="container">
        <div class="checkoutLayout">
            <div class="returnCart">
                <a href="/lyons.peru/hombres/hombres.php" style="color: rgb(135, 189, 191); text-decoration: none; font-weight: bold; font-size: 30px;">Seguir Comprando</a>
                <h1>Lista de productos</h1>
                <div class="list">
                    <div class="item">
                        <div class="cart-items"></div>
                    </div>
                </div>
            </div>
            <div class="right">
                <h1>Resumen de la Compra</h1>
                <div class="form"></div>
                <div class="return">
                    <div class="row">
                        <div>Precio Final (aplicado IGV)</div>
                        <div class="cart-total">S/<?php echo number_format($precioTotal, 2); ?></div>
                    </div>
                    <!-- Agrega aquí el código para el botón de Pago Contraentrega -->
                    <a href="../pago/checkoutdatos.php"><button class="buttonCheckout" id="contraentregaButton">Proceder al Pago</button></a>
                </div>
            </div>
        </div>
    </div>
    <script src="bootstrap-5.0.2-dist/js/bootstrap.min.js"></script>
    <script src="https://kit.fontawesome.com/81581fb069.js" crossorigin="anonymous"></script>
    <script src="/LYONS.PERU/hombres/camisetas/index.js"></script>
    <script>
        // Agrega aquí la lógica necesaria para el botón de Pago Contraentrega
        document.getElementById("contraentregaButton").addEventListener("click", function() {
            // Agrega aquí la lógica para el pago contraentrega
            // Puedes redirigir a una página de confirmación o realizar otras acciones necesarias.
            alert("Dirigiendose a la Pestaña de Pago");
        });

        // Otras funciones y lógica necesarias para la página de checkout
    </script>
</body>
</html>
