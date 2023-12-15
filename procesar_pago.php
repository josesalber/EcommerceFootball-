<?php
session_start();
require_once '../conexion.php';

$successMessage = "";
$errorMessage = "";

// Verifica si se envió el formulario de pago
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verifica si se recibieron datos de pago
    $postData = file_get_contents("php://input");
    $postData = json_decode($postData, true);

    if (empty($postData) || !isset($postData['precioTotal'])) {
        http_response_code(400); // Bad Request
        echo json_encode(['error' => 'No se recibieron datos de pago']);
        exit;
    }

    // Obtén el precio total desde los datos del formulario
    $precioTotal = $postData['precioTotal'];

    // Resto del código para la inserción en la base de datos
    $correo = $postData['correo'];
    $nombresApellidos = $postData['nombres_apellidos'];
    $documentoIdentidad = $postData['documento_identidad'];
    $telefono = $postData['phone'];
    $direccion = $postData['address'];
    $ciudad = $postData['country'];
    $distrito = $postData['city'];

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
    $insertPedidoSQL = "INSERT INTO pedidos (nombre_usuario, correo, nombres_apellidos, documento_identidad, telefono, direccion, ciudad, distrito, precio_total) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    // Preparar la consulta
    $stmtPedido = $conn->prepare($insertPedidoSQL);

    if (!$stmtPedido) {
        http_response_code(500);
        $errorMessage = "Error en la preparación de la consulta de pedidos: " . $conn->error;
    } else {
        // Vincular parámetros y ejecutar la consulta
        $stmtPedido->bind_param("ssssssssd", $nombreDeUsuario, $correo, $nombresApellidos, $documentoIdentidad, $telefono, $direccion, $ciudad, $distrito, $precioTotal);
        $stmtPedido->execute();

        if ($stmtPedido->affected_rows > 0) {
            $pedidoId = $stmtPedido->insert_id;

            // Verificar si $_SESSION['cart'] está definido y es un array
            if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {

                // Insertar en la tabla detalles_pedido
                $insertProductoSQL = "INSERT INTO detalles_pedido (pedido_id, producto_id, cantidad) VALUES (?, ?, ?)";
                $stmtProducto = $conn->prepare($insertProductoSQL);

                if ($stmtProducto) {
                    foreach ($_SESSION['cart'] as $cartProduct) {
                        if (isset($cartProduct['id']) && isset($cartProduct['quantity'])) {
                            $productoId = $cartProduct['id'];
                            $cantidad = $cartProduct['quantity'];

                            $stmtProducto->bind_param("iii", $pedidoId, $productoId, $cantidad);
                            $stmtProducto->execute();

                            if ($stmtProducto->affected_rows <= 0) {
                                http_response_code(500);
                                $errorMessage = "Error al insertar productos en la tabla detalles_pedido: " . $stmtProducto->error;
                                $conn->rollback();
                                break;
                            }
                        }
                    }

                    $stmtProducto->close();
                } else {
                    http_response_code(500);
                    $errorMessage = "Error en la preparación de la consulta de detalles_pedido: " . $conn->error;
                }
            }

            $conn->commit();
            $successMessage = "Datos insertados correctamente en las tablas pedidos y detalles_pedido.";

            $_SESSION['cart'] = [];
        } else {
            http_response_code(500);
            $errorMessage = "Error al insertar el pedido en la tabla pedidos: " . $stmtPedido->error;
            $conn->rollback();
        }
    }

    $stmtPedido->close();
    $conn->close();

    // Devolver respuesta JSON
    header('Content-Type: application/json');
    echo json_encode(['success' => $successMessage, 'error' => $errorMessage]);
    exit;
}
?>
