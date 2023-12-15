document.addEventListener("DOMContentLoaded", function () {
    const cartIcon = document.querySelector(".cart-icon");
    const cart = document.querySelector(".cart");
    const closeCartButton = document.querySelector(".close-cart");
    const cartItems = document.querySelector(".cart-items");
    const cartTotal = document.querySelector(".cart-total");
    const sizeSelect = document.getElementById("size");
    const quantityInput = document.querySelector(".input-quantity");
    const incrementButton = document.querySelector("#increment");
    const decrementButton = document.querySelector("#decrement");
    const payButton = document.querySelector(".pay-button");
    const clearButton = document.querySelector(".clear-button");
    const cartCount = document.querySelector(".cart-count");

    let isCartVisible = false;
    let cartProducts = [];
    let stockProducto = 100; // Assuming an initial stock of 100, replace with the actual initial stock

    // Mapeo de tallas numéricas a tallas deseadas
    const tallaMapping = {
        "40": "S",
        "42": "M",
        "43": "L",
        "44": "XL",
    };

    function removeItemFromCart(index) {
        // Add back the quantity to the stock when removing an item from the cart
        stockProducto += cartProducts[index].quantity;

        cartProducts.splice(index, 1);
        saveCartToLocalStorage();
        renderCartItems(cartProducts);
        updateCartTotal();
        updateCartIcon();
        updateCartCount();
    }

    function calculateTotalWithIGV(totalWithoutIGV) {
        const igvRate = 0.18;
        const totalWithIGV = totalWithoutIGV * (1 + igvRate);
        return totalWithIGV;
    }

    function updateCartTotalWithIGV() {
        const totalWithoutIGV = cartProducts.reduce((acc, product) => acc + product.price * product.quantity, 0);
        const totalWithIGV = calculateTotalWithIGV(totalWithoutIGV);
        cartTotal.textContent = `S/${totalWithIGV.toFixed(2)}`;
        localStorage.setItem("cartTotal", totalWithIGV);
    }

    // Load the cart from local storage
    const savedCart = localStorage.getItem("cart");
    if (savedCart) {
        cartProducts = JSON.parse(savedCart);
        renderCartItems(cartProducts);
        updateCartTotalWithIGV();
    }

    // Function to close the cart
    function closeCart() {
        cart.style.display = "none";
        isCartVisible = false;
    }

    function addToCart(productName, price, size, quantity) {
        if (quantity > stockProducto) {
            // Mostrar mensaje de error en lugar de alert
            const errorMessage = document.createElement("div");
            errorMessage.textContent = "No hay suficiente stock disponible";
            errorMessage.style.color = "red";
            // Agregar el mensaje al DOM donde sea necesario
            return;
        }

        const uniqueId = generateUniqueId();
        const existingProductIndex = cartProducts.findIndex(
            (product) => product.productName === productName && product.size === size
        );

        if (existingProductIndex !== -1) {
            cartProducts[existingProductIndex].quantity += quantity;
        } else {
            // Solo resta el stock si el producto no está en el carrito
            stockProducto -= quantity;
            cartProducts.push({ uniqueId, productName, price, size, quantity });
        }

        saveCartToLocalStorage();
        renderCartItems(cartProducts);
        updateCartTotalWithIGV();
        updateStockInDatabase(1, stockProducto);
        updateCartIcon();
        updateCartCount();

        setTimeout(() => {
            // Solo incrementa el stock si ha pasado el tiempo y el producto no está en el carrito
            if (existingProductIndex === -1) {
                stockProducto += quantity;
                updateStockInDatabase(1, stockProducto);
            }
        }, 3 * 60 * 60 * 1000);
    }
    
    

    function updateStockInDatabase(productId, newStock) {
        // Replace with your actual database connection details
        const servername = "localhost";
        const username = "root";
        const password = "";
        const dbname = "ecommerce_db";

        const conn = new mysqli(servername, username, password, dbname);

        if (conn.connect_error) {
            console.error("Error de conexión a la base de datos: " + conn.connect_error);
            return;
        }

        const sql = "UPDATE productos SET stock = ? WHERE id = ?";
        const stmt = conn.prepare(sql);

        if (!stmt) {
            console.error("Error en la preparación de la consulta: " + conn.error);
            return;
        }

        stmt.bind_param("ii", newStock, productId);
        stmt.execute();

        if (stmt.affected_rows > 0) {
            console.log("Stock actualizado en la base de datos.");
        } else {
            console.error("Error al actualizar el stock: " + stmt.error);
        }

        stmt.close();
        conn.close();
    }

    function generateUniqueId() {
        return Date.now() + Math.random();
    }

    function renderCartItems(products) {
        cartItems.innerHTML = "";
        products.forEach((product, index) => {
            const cartItem = document.createElement("li");
            cartItem.innerHTML = `
                <span>${product.quantity}x ${product.productName} (Talla: ${product.size})</span>
                <span>S/${(product.price * product.quantity).toFixed(2)}</span>
                <button class="remove-button" data-index="${index}">Eliminar</button>
            `;

            cartItem.querySelector(".remove-button").style.backgroundColor = "#ff5733";
            cartItem.querySelector(".remove-button").style.color = "#fff";
            cartItem.querySelector(".remove-button").style.border = "none";
            cartItem.querySelector(".remove-button").style.padding = "5px 10px";
            cartItem.querySelector(".remove-button").style.borderRadius = "5px";
            cartItem.querySelector(".remove-button").style.cursor = "pointer";
            cartItem.querySelector(".remove-button").style.marginLeft = "10px";

            cartItems.appendChild(cartItem);
        });
    }

    updateCartCount();

    cartItems.addEventListener("click", function (event) {
        if (event.target.classList.contains("remove-button")) {
            const index = event.target.getAttribute("data-index");
            removeItemFromCart(index);
            updateCartCount();
        }
    });

    function updateCartTotal() {
        const total = cartProducts.reduce((acc, product) => acc + product.price * product.quantity, 0);
        cartTotal.textContent = `S/${total.toFixed(2)}`;
        localStorage.setItem("cartTotal", total);
    }

    function saveCartToLocalStorage() {
        localStorage.setItem("cart", JSON.stringify(cartProducts));
    }

    cartIcon.addEventListener("click", function () {
        isCartVisible = !isCartVisible;
        cart.style.display = isCartVisible ? "block" : "none";
    });

    closeCartButton.addEventListener("click", closeCart);

    incrementButton.addEventListener("click", () => {
        let valueByDefault = parseInt(quantityInput.value);
        valueByDefault += 1;
        quantityInput.value = valueByDefault;
    });

    decrementButton.addEventListener("click", () => {
        let valueByDefault = parseInt(quantityInput.value);
        if (valueByDefault === 1) {
            return;
        }
        valueByDefault -= 1;
        quantityInput.value = valueByDefault;
    });

    payButton.addEventListener("click", function () {
        const totalPrice = cartProducts.reduce((total, product) => total + product.price * product.quantity, 0);
        const totalPriceWithIGV = calculateTotalWithIGV(totalPrice);
        localStorage.setItem("cartTotal", totalPriceWithIGV);
        window.location.href = "/lyons.peru/pago/checkout.php";
        updateCartTotalWithIGV();
    });

    clearButton.addEventListener("click", function () {
        cartItems.innerHTML = "";
        cartTotal.textContent = "0.00";
        cartProducts = [];
        saveCartToLocalStorage();
        cartCount.textContent = "0";
    });

    const addToCartButtons = document.querySelectorAll(".btn-add-to-cart");

    addToCartButtons.forEach((button) => {
        button.addEventListener("click", function () {
            const productDiv = button.closest(".product");
            const productName = productDiv.getAttribute("data-product-name");
            const price = parseFloat(productDiv.getAttribute("data-product-price"));
            const selectedSize = sizeSelect.value;
            const size = tallaMapping[selectedSize];
            const quantity = parseInt(quantityInput.value, 10);
            addToCart(productName, price, size, quantity);
        });
    });

    function updateCartCount() {
        const totalProducts = cartProducts.reduce((acc, product) => acc + product.quantity, 0);
        cartCount.textContent = totalProducts.toString();
        console.log("Total de productos en el carrito: " + totalProducts);
    }
});
