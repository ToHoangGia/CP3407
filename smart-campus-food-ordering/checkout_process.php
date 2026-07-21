<?php
session_start();

header("Content-Type: application/json; charset=UTF-8");

require_once __DIR__ . "/db.php";

/*
 * Only allow POST requests.
 */
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);

    echo json_encode([
        "success" => false,
        "message" => "Only POST requests are allowed."
    ]);

    exit();
}

/*
 * A user must be logged in.
 */
if (!isset($_SESSION["user_id"])) {
    http_response_code(401);

    echo json_encode([
        "success" => false,
        "message" =>
            "Please log in before placing an order."
    ]);

    exit();
}

/*
 * Read the JSON request body.
 */
$request_body =
    file_get_contents("php://input");

$data = json_decode($request_body, true);

if (json_last_error() !== JSON_ERROR_NONE) {
    http_response_code(400);

    echo json_encode([
        "success" => false,
        "message" => "Invalid checkout data."
    ]);

    exit();
}

/*
 * Validate cart structure.
 */
if (
    !isset($data["items"]) ||
    !is_array($data["items"]) ||
    count($data["items"]) === 0
) {
    http_response_code(400);

    echo json_encode([
        "success" => false,
        "message" => "The shopping cart is empty."
    ]);

    exit();
}

$user_id = (int) $_SESSION["user_id"];

$conn->begin_transaction();

try {
    $validated_items = [];
    $total_price = 0.00;

    /*
     * Read each item's real price from MySQL.
     *
     * We do not trust the price sent by JavaScript
     * because users could change it in browser tools.
     */
    $item_query = $conn->prepare(
        "
        SELECT
            item_id,
            item_name,
            price
        FROM menu_items
        WHERE item_id = ?
          AND available = TRUE
        "
    );

    if ($item_query === false) {
        throw new Exception(
            "Unable to prepare menu item query."
        );
    }

    foreach ($data["items"] as $cart_item) {
        $item_id =
            isset($cart_item["itemId"])
                ? (int) $cart_item["itemId"]
                : 0;

        $quantity =
            isset($cart_item["quantity"])
                ? (int) $cart_item["quantity"]
                : 0;

        if ($item_id <= 0) {
            throw new Exception(
                "An invalid menu item was submitted."
            );
        }

        if ($quantity <= 0 || $quantity > 100) {
            throw new Exception(
                "An invalid quantity was submitted."
            );
        }

        $item_query->bind_param(
            "i",
            $item_id
        );

        if (!$item_query->execute()) {
            throw new Exception(
                "Unable to check a menu item."
            );
        }

        $item_result =
            $item_query->get_result();

        if ($item_result->num_rows !== 1) {
            throw new Exception(
                "A menu item is unavailable or no longer exists."
            );
        }

        $menu_item =
            $item_result->fetch_assoc();

        $unit_price =
            (float) $menu_item["price"];

        $subtotal =
            $unit_price * $quantity;

        $total_price =
            $total_price + $subtotal;

        $validated_items[] = [
            "item_id" => $item_id,
            "quantity" => $quantity,
            "unit_price" => $unit_price,
            "subtotal" => $subtotal
        ];
    }

    $item_query->close();

    /*
     * Create the main order record.
     */
    $order_statement = $conn->prepare(
        "
        INSERT INTO orders (
            user_id,
            total_price,
            status
        )
        VALUES (
            ?,
            ?,
            'pending'
        )
        "
    );

    if ($order_statement === false) {
        throw new Exception(
            "Unable to prepare order."
        );
    }

    $order_statement->bind_param(
        "id",
        $user_id,
        $total_price
    );

    if (!$order_statement->execute()) {
        throw new Exception(
            "Unable to create the order."
        );
    }

    $order_id =
        (int) $conn->insert_id;

    $order_statement->close();

    /*
     * Save each food item in order_items.
     */
    $order_item_statement = $conn->prepare(
        "
        INSERT INTO order_items (
            order_id,
            item_id,
            quantity,
            unit_price,
            subtotal
        )
        VALUES (
            ?,
            ?,
            ?,
            ?,
            ?
        )
        "
    );

    if ($order_item_statement === false) {
        throw new Exception(
            "Unable to prepare order items."
        );
    }

    foreach ($validated_items as $validated_item) {
        /*
         * mysqli bind_param requires variables,
         * not direct array values.
         */
        $saved_item_id =
            (int) $validated_item["item_id"];

        $saved_quantity =
            (int) $validated_item["quantity"];

        $saved_unit_price =
            (float) $validated_item["unit_price"];

        $saved_subtotal =
            (float) $validated_item["subtotal"];

        $order_item_statement->bind_param(
            "iiidd",
            $order_id,
            $saved_item_id,
            $saved_quantity,
            $saved_unit_price,
            $saved_subtotal
        );

        if (!$order_item_statement->execute()) {
            throw new Exception(
                "Unable to save an order item."
            );
        }
    }

    $order_item_statement->close();

    /*
     * Save everything together.
     */
    $conn->commit();

    echo json_encode([
        "success" => true,
        "orderId" => $order_id,
        "total" => number_format(
            $total_price,
            2,
            ".",
            ""
        )
    ]);

} catch (Throwable $error) {
    $conn->rollback();

    /*
     * Store the detailed error in server logs
     * rather than exposing database details.
     */
    error_log(
        "Checkout error: " .
        $error->getMessage()
    );

    http_response_code(500);

    echo json_encode([
        "success" => false,
        "message" =>
            "The order could not be saved. " .
            "Please try again."
    ]);
}

$conn->close();
?>