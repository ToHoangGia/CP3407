<?php
include "db.php";

$sql = "
    SELECT 
        stores.store_name,
        menu_items.item_name,
        menu_items.description,
        menu_items.price,
        menu_items.image
    FROM menu_items
    INNER JOIN stores ON menu_items.store_id = stores.store_id
    WHERE menu_items.available = TRUE
    ORDER BY stores.store_id, menu_items.item_id
";

$result = $conn->query($sql);
$current_store = "";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Smart Campus Food Ordering System</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

<header>
    <h1>Smart Campus Food Ordering System</h1>
    <p>Order food from different campus stalls</p>

    <nav>
        <a href="index.php">Home</a>
        <a href="login.html">Login</a>
        <a href="register.html">Register</a>
    </nav>
</header>

<main>

    <section class="menu">
        <h2>Campus Food Stalls</h2>

        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {

                if ($current_store != $row["store_name"]) {
                    $current_store = $row["store_name"];
                    echo "<h3 class='stall-title'>" . htmlspecialchars($current_store) . "</h3>";
                }

                $item_name = htmlspecialchars($row["item_name"]);
                $description = htmlspecialchars($row["description"]);
                $price = number_format($row["price"], 2);
                $image = htmlspecialchars($row["image"]);

                $js_item_name = json_encode($row["item_name"]);
                $js_price = $row["price"];

                echo "
                <div class='food-item'>
                    <div class='food-text'>
                        <h4>$item_name</h4>
                        <p>Price: $$price</p>
                        <p>$description</p>
                        <button type='button' onclick='addToCart($js_item_name, $js_price)'>Add to Cart</button>
                    </div>

                    <img src='images/$image' alt='$item_name'>
                </div>
                ";
            }
        } else {
            echo "<p>No menu items available.</p>";
        }
        ?>

    </section>

    <section class="cart-section">
        <h2>Shopping Cart</h2>

        <ul id="cartList"></ul>

        <p id="totalPrice">Total: $0.00</p>

        <button type="button" onclick="checkout()">Place Order</button>
        <button type="button" onclick="clearCart()">Clear Cart</button>
    </section>

</main>

<footer>
    <p>&copy; 2026 Smart Campus Food Ordering System</p>
</footer>

<script src="script.js"></script>

</body>
</html>

<?php
$conn->close();
?>