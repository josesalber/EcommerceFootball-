<?php
session_start();
require_once '../conexion.php';

$successMessage = "";
$errorMessage = "";
$precioTotal = 0;

// Verificar si $_SESSION['cart'] está definido y es un array
if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
    // Si no está definido o no es un array, inicializarlo como un array vacío
    $_SESSION['cart'] = [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $postData = file_get_contents("php://input");
    $postData = json_decode($postData, true);

    if ($postData === null || json_last_error() !== JSON_ERROR_NONE) {
        http_response_code(400); 
        echo json_encode(['error' => 'Error al decodificar los datos de pago']);
        exit;
    }

    $precioTotal = isset($postData['precioTotal']) ? $postData['precioTotal'] : 0;

    $requiredFields = ['correo', 'nombres_apellidos', 'documento_identidad', 'phone', 'address', 'country', 'city', 'productos'];
    $missingFields = array_filter($requiredFields, function ($field) use ($postData) {
        return empty($postData[$field]);
    });

    if (!empty($missingFields)) {
        $errorMessage = "Por favor, complete todos los campos requeridos: " . implode(', ', $missingFields);
    } else {
        $correo = $postData['correo'];
        $nombresApellidos = $postData['nombres_apellidos'];
        $documentoIdentidad = $postData['documento_identidad'];
        $telefono = $postData['phone'];
        $direccion = $postData['address'];
        $ciudad = $postData['country'];
        $distrito = $postData['city'];
        $productos = $postData['productos']; // La lista de productos

        // Construir la lista de productos como una cadena de texto
        $listaProductos = [];

        foreach ($productos as $producto) {
            $productoStr = "{$producto['quantity']}x {$producto['productName']} (Talla: {$producto['size']})";
            $listaProductos[] = $productoStr;
        }

        // Convertir la lista de productos a una cadena de texto
        $listaProductosText = implode(', ', $listaProductos);

        // Conexión a la base de datos
        $conn = new mysqli($host, $usuario, $contrasena, $base_de_datos);

        // Verificar la conexión
        if ($conn->connect_error) {
            http_response_code(500); // Internal Server Error
            die("Error en la conexión a la base de datos: " . $conn->connect_error);
        }

        // Iniciar transacción
        $conn->begin_transaction();

        // Insertar en la tabla de pedidos
        $nombreDeUsuario = isset($_SESSION['username']) ? $_SESSION['username'] : null;
        $estadoPedido = "En Proceso"; // Valor predeterminado para el nuevo campo estado
        $insertPedidoSQL = "INSERT INTO pedidos (nombre_usuario, correo, nombres_apellidos, documento_identidad, telefono, direccion, ciudad, distrito, precio_total, estado, lista_productos) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        // Preparar la consulta
        $stmtPedido = $conn->prepare($insertPedidoSQL);

        if (!$stmtPedido) {
            http_response_code(500);
            $errorMessage = "Error en la preparación de la consulta de pedidos: " . $conn->error;
        } else {
            // Vincular parámetros y ejecutar la consulta
            $stmtPedido->bind_param("ssssssssdss", $nombreDeUsuario, $correo, $nombresApellidos, $documentoIdentidad, $telefono, $direccion, $ciudad, $distrito, $precioTotal, $estadoPedido, $listaProductosText);

            $stmtPedido->execute();

            if ($stmtPedido->affected_rows > 0) {
                $conn->commit();
                $successMessage = "Datos insertados correctamente en la tabla pedidos.";
            } else {
                http_response_code(500);
                $errorMessage = "Error al insertar el pedido en la tabla pedidos: " . $stmtPedido->error;
                $conn->rollback();
            }

            // Restablecer el carrito después de completar la transacción
            unset($_SESSION['cart']);
            unset($_SESSION['cartTotal']);
        }

        $stmtPedido->close();
        $conn->close();

        // Devolver respuesta JSON
        header('Content-Type: application/json');
        echo json_encode(['success' => $successMessage, 'error' => $errorMessage]);
        exit;
    }
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
        <div id="message" class="alert" style="display: none;"></div>

        <div class="checkoutLayout">
            <div class="returnCart">
                <a href="/lyons.peru/hombres/hombres.php" style="color: rgb(135, 189, 191); text-decoration: none; font-weight: bold; font-size: 30px;">Seguir Comprando</a>
                <h1>Lista de productos</h1>
                <div class="list">
                    <div class="item">
                        <div class="cart-items">
                        </div>
                    </div>
                </div>
            </div>
            <div class="right">
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="identification">
                        <h1>Detalles de la Facturacion</h1>
                        <p>Solicitamos únicamente la información esencial para la finalización de la compra.</p>
                        <form method="post" action="checkoutdatos.php">
                            <div class="form">
                                <div class="group">
                                    <label for="correo">Correo</label>
                                    <input type="email" name="correo" id="correo" pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$" required>
                                </div>
                                <div class="group">
                                    <label for="nombres_apellidos">Nombres y Apellidos</label>
                                    <input type="text" name="nombres_apellidos" id="nombres_apellidos" required>
                                </div>
                                <div class="group">
                                    <label for="documento_identidad">Documento de Identidad</label>
                                    <input type="text" name="documento_identidad" id="documento_identidad" maxlength="8" required>
                                </div>
                                <div class="group">
                                    <label for="phone">Teléfono / Móvil</label>
                                    <input type="text" name="phone" id="phone" maxlength="9" required>
                                </div>
                                <div class="group">
                                    <label for="address">Dirección</label>
                                    <input type="text" name="address" id="address" required>
                                </div>
                                <div class="group">
                                    <label for="country">Ciudad</label>
                                    <select name="country" id="country">
                                        <option value="Lima">Lima</option>
                                    </select>
                                </div>
                                <div class="group">
                                    <label for="city">Distrito</label>
                                    <select name="city" id="city">
                                        <option value="">Elegir..</option>
                                        <option value="Surco">Surco</option>
                                        <option value="La Molina">La Molina</option>
                                        <option value="Chorrillos">Chorrillos</option>
                                        <option value="Jesus Maria">Jesus Maria</option>
                                    </select>
                                </div>
                                </div>
                                <div class="return">
                                    <div>Precio Final (aplicado IGV)</div>
                                    <div class="cart-total">S/</div>
                                </div>
                                <input type="hidden" name="precioTotal" id="precioTotal" value="<?php echo isset($_SESSION['cartTotal']) ? $_SESSION['cartTotal'] : 0; ?>">
                                <button class="buttonCheckout" type="submit" name="submit" id="contraentregaButton">PAGO CONTRAENTREGA</button>
                                <div id="message" class="alert" style="display: none;"></div>
                                <?php if (!empty($successMessage)) : ?>
                                    <div class="alert alert-success mt-3">
                                        <?php echo $successMessage; ?>
                                    </div>
                                <?php endif; ?>
                                <?php if (!empty($errorMessage)) : ?>
                                    <div class="alert alert-danger mt-3">
                                        <?php echo $errorMessage; ?>
                                    </div>
                                <?php endif; ?>
                            </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <footer class="bg-dark py-5">
        <div class="container">
            <!-- Pie de página ... -->
        </div>
    </footer>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="bootstrap-5.0.2-dist/js/bootstrap.min.js"></script>
    <script src="../hombres/camisetas/index.js"></script>

    <script>
        $(document).ready(function () {
            $('#contraentregaButton').click(function (e) {
                e.preventDefault();

                // Obtener datos del formulario
                var formData = {
                    correo: $('#correo').val(),
                    nombres_apellidos: $('#nombres_apellidos').val(),
                    documento_identidad: $('#documento_identidad').val(),
                    phone: $('#phone').val(),
                    address: $('#address').val(),
                    country: $('#country').val(),
                    city: $('#city').val(),
                    precioTotal: parseFloat($('#precioTotal').val()),
                    productos: []
                };

                // Recorrer cada producto en el carrito y agregarlo a la lista
                $('.cart-items li').each(function () {
                    var quantity = $(this).find('span:first-child').text().split('x')[0].trim();
                    var productName = $(this).find('span:first-child').text().split('x')[1].trim().split('(Talla:')[0].trim();
                    var size = $(this).find('span:first-child').text().split('(Talla:')[1].trim().slice(0, -1);

                    formData.productos.push({
                        quantity: parseInt(quantity),
                        productName: productName,
                        size: size
                    });
                });

                // Realizar la solicitud Ajax
                $.ajax({
                    type: 'POST',
                    url: 'checkoutdatos.php',
                    data: JSON.stringify(formData),
                    contentType: 'application/json',
                    dataType: 'json',
                    success: function (response) {
                        console.log(response);

                        $('#message').html(response.success).show();

                        if (response.success) {
                            window.location.href = '/lyons.peru/hombres/perfil.php';
                        }
                    },
                    error: function (xhr, status, error) {
                        console.log(xhr.responseText);
                        console.log(status);
                        console.log(error);

                        $('#message').html("Error en el procesamiento del pago. Por favor, inténtelo de nuevo.").show();
                    },
                    fail: function (jqXHR, textStatus, errorThrown) {
                        console.log(jqXHR.responseText);
                        console.log(textStatus);
                        console.log(errorThrown);

                        $('#message').html("Error en el procesamiento del pago. Por favor, inténtelo de nuevo.").show();
                    }
                });
            });
        });


    </script>








</script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
    const cartTotalElement = document.querySelector(".cart-total");
    const storedCartTotal = localStorage.getItem("cartTotal");

    if (storedCartTotal) {
            cartTotalElement.textContent = `S/${parseFloat(storedCartTotal).toFixed(2)}`;
            
            // Actualiza el valor del campo oculto precioTotal
            document.getElementById("precioTotal").value = parseFloat(storedCartTotal).toFixed(2);
        }
    });
    </script>
</body>
</html>
