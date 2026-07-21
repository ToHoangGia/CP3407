<?php
session_start();

require_once __DIR__ . "/db.php";

$sql = "
    SELECT
        stores.store_id,
        stores.store_name,
        menu_items.item_id,
        menu_items.item_name,
        menu_items.description,
        menu_items.price,
        menu_items.image
    FROM menu_items
    INNER JOIN stores
        ON menu_items.store_id = stores.store_id
    WHERE menu_items.available = TRUE
    ORDER BY stores.store_id, menu_items.item_id
";

$result = $conn->query($sql);

if ($result === false) {
    die("Menu query failed: " . $conn->error);
}

$current_store = "";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Smart Campus Food Ordering System</title>

    <link rel="stylesheet" href="style.css">
</head>

<body>

<header>
    <h1>Smart Campus Food Ordering System</h1>

    <p>Order food from different campus stalls</p>

    <nav>
        <a href="index.php">Home</a>

        <?php if (isset($_SESSION["user_id"])): ?>

            <span>
                Welcome,
                <?php
                echo htmlspecialchars(
                    $_SESSION["full_name"] ?? "User",
                    ENT_QUOTES,
                    "UTF-8"
                );
                ?>
            </span>

        <?php else: ?>

            <a href="login.html">Login</a>
            <a href="register.html">Register</a>

        <?php endif; ?>
    </nav>
</header>

<main>

    <section class="menu">

        <h2>Campus Food Stalls</h2>

        <?php if ($result->num_rows > 0): ?>

            <?php while ($row = $result->fetch_assoc()): ?>

                <?php
                if ($current_store !== $row["store_name"]) {
                    $current_store = $row["store_name"];
                ?>

                    <h3 class="stall-title">
                        <?php
                        echo htmlspecialchars(
                            $current_store,
                            ENT_QUOTES,
                            "UTF-8"
                        );
                        ?>
                    </h3>

                <?php
                }

                $item_id = (int) $row["item_id"];
                $price = (float) $row["price"];

                $item_name = htmlspecialchars(
                    $row["item_name"],
                    ENT_QUOTES,
                    "UTF-8"
                );

                $description = htmlspecialchars(
                    $row["description"] ?? "",
                    ENT_QUOTES,
                    "UTF-8"
                );

                $image = htmlspecialchars(
                    basename($row["image"] ?? ""),
                    ENT_QUOTES,
                    "UTF-8"
                );

                /*
                 * json_encode safely creates a JavaScript string.
                 * JSON_HEX flags protect the inline onclick attribute.
                 */
                $js_item_name = json_encode(
                    $row["item_name"],
                    JSON_HEX_TAG |
                    JSON_HEX_AMP |
                    JSON_HEX_APOS |
                    JSON_HEX_QUOT
                );
                ?>

                <div class="food-item">

                    <div class="food-text">

                        <h4>
                            <?php echo $item_name; ?>
                        </h4>

                        <p>
                            Price:
                            $<?php echo number_format($price, 2); ?>
                        </p>

                        <p>
                            <?php echo $description; ?>
                        </p>

                        <button
                            type="button"
                            onclick='addToCart(
                                <?php echo $item_id; ?>,
                                <?php echo $js_item_name; ?>,
                                <?php echo $price; ?>
                            )'
                        >
                            Add to Cart
                        </button>

                    </div>

                    <?php if ($image !== ""): ?>

                        <img
                            src="images/<?php echo $image; ?>"
                            alt="<?php echo $item_name; ?>"
                        >

                    <?php endif; ?>

                </div>

            <?php endwhile; ?>

        <?php else: ?>

            <p>No menu items are currently available.</p>

        <?php endif; ?>

    </section>

    <section class="cart-section">

        <h2>Shopping Cart</h2>

        <ul id="cartList">
            <li>Your cart is empty.</li>
        </ul>

        <p id="totalPrice">
            Total: $0.00
        </p>

        <button
            type="button"
            onclick="checkout()"
        >
            Place Order
        </button>

        <button
            type="button"
            onclick="clearCart()"
        >
            Clear Cart
        </button>

        <?php if (!isset($_SESSION["user_id"])): ?>

            <p>
                You must
                <a href="login.html">log in</a>
                before placing an order.
            </p>

        <?php endif; ?>

    </section>

</main>

<footer>
    <p>
        &copy; 2026 Smart Campus Food Ordering System
    </p>
</footer>

<script src="script.js"></script>

</body>
</html>

<?php
$conn->close();
?>