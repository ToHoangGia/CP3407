<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Campus Food Ordering System</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <header>
        <h1>Smart Campus Food Ordering System</h1>
        <p>Order food easily from campus stalls</p>
        <nav>
            <a href="index.php">Home</a>
            <a href="login.html">Login</a>
            <a href="register.html">Register</a>
        </nav>
    </header>

    <main>

        <section class="menu">

            <h2>Campus Food Stalls</h2>

            <h3 class="stall-title">Asian Delight</h3>

            <div class="food-item">
                <div class="food-text">
                    <h4>Chicken Rice</h4>
                    <p>Price: $5.50</p>
                   <p>Steamed chicken served with rice.</p>
                   <button type="button" onclick="addToCart('Chicken Rice', 5.50)">Add to Cart</button>
                </div>

                <img src="images/chicken-rice.jpg" alt="Chicken Rice">
            </div>

            <div class="food-item">
                <div class="food-text">
                    <h4>Fried Noodles</h4>
                    <p>Price: $6.00</p>
                    <p>Fried noodles with vegetables.</p>
                    <button onclick="addToCart('Fried Noodles', 6.00)">Add to Cart</button>
                </div>

                <img src="images/fried-noodles.jpg" alt="Fried Noodles">
           </div>


            <h3 class="stall-title">Drink Corner</h3>

            <div class="food-item">
                <div class="food-text">
                    <h4>Bubble Tea</h4>
                    <p>Price: $4.50</p>
                    <p>Fresh milk tea with pearls.</p>
                    <button onclick="addToCart('Bubble Tea', 4.50)">Add to Cart</button>
                </div>

                <img src="images/bubble-tea.jpg" alt="Bubble Tea">
            </div>

            <div class="food-item">
                <div class="food-text">
                    <h4>Iced Coffee</h4>
                    <p>Price: $3.50</p>
                    <p>Cold brewed coffee.</p>
                    <button onclick="addToCart('Iced Coffee', 3.50)">Add to Cart</button>
                </div>

                <img src="images/iced-coffee.jpg" alt="Iced Coffee">
            </div>


            <h3 class="stall-title">Western Kitchen</h3>

            <div class="food-item">
                <div class="food-text">
                    <h4>Burger Set</h4>
                    <p>Price: $8.50</p>
                    <p>Burger served with fries.</p>
                    <button onclick="addToCart('Burger Set', 8.50)">Add to Cart</button>
                </div>

                <img src="images/burger.jpg" alt="Burger">
            </div>
        </section>

        <section class="cart-section">
            <h2>Shopping Cart</h2>

            <ul id="cartList"></ul>

            <p id="totalPrice">Total: $0.00</p>

            <button onclick="checkout()">Place Order</button>
            <button onclick="clearCart()">Clear Cart</button>
        </section>


    </main>

    <footer>
        <p>&copy; 2026 Smart Campus Food Ordering System</p>
    </footer>

    <script src="script.js"></script>

</body>
</html>