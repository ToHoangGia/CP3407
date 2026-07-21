"use strict";

/*
 * LOGIN VALIDATION
 */
function validateLogin() {
    var loginForm = document.forms["loginForm"];

    if (!loginForm) {
        alert("Login form could not be found");
        return false;
    }

    var email = loginForm["email"].value.trim();
    var password = loginForm["password"].value;

    if (email === "") {
        alert("Email must be filled out");
        return false;
    }

    if (password === "") {
        alert("Password must be filled out");
        return false;
    }

    /*
     * Returning true allows login_process.php
     * to process the submitted form.
     */
    return true;
}


/*
 * REGISTER VALIDATION
 */
function validateRegister() {
    var registerForm = document.forms["registerForm"];

    if (!registerForm) {
        alert("Register form could not be found");
        return false;
    }

    var name =
        registerForm["registerName"].value.trim();

    var email =
        registerForm["registerEmail"].value.trim();

    var password =
        registerForm["registerPassword"].value;

    var confirmPassword =
        registerForm["confirmPassword"].value;

    if (name === "") {
        alert("Full name must be filled out");
        return false;
    }

    if (email === "") {
        alert("Email must be filled out");
        return false;
    }

    if (password === "") {
        alert("Password must be filled out");
        return false;
    }

    if (password.length < 6) {
        alert("Password must contain at least 6 characters");
        return false;
    }

    if (confirmPassword === "") {
        alert("Please confirm your password");
        return false;
    }

    if (password !== confirmPassword) {
        alert("Passwords do not match");
        return false;
    }

    /*
     * PHP will show the real registration result
     * after attempting to save the user in MySQL.
     */
    return true;
}


/*
 * SHOPPING CART
 */
var cart = [];
var total = 0;


/*
 * Add an item or increase its quantity.
 */
function addToCart(itemId, foodName, price) {
    itemId = Number(itemId);
    price = Number(price);

    if (
        !Number.isInteger(itemId) ||
        itemId <= 0 ||
        !Number.isFinite(price) ||
        price < 0
    ) {
        alert("Invalid menu item");
        return;
    }

    var existingItem = null;

    for (var i = 0; i < cart.length; i++) {
        if (cart[i].itemId === itemId) {
            existingItem = cart[i];
            break;
        }
    }

    if (existingItem !== null) {
        existingItem.quantity =
            existingItem.quantity + 1;
    } else {
        cart.push({
            itemId: itemId,
            name: foodName,
            price: price,
            quantity: 1
        });
    }

    displayCart();

    alert(foodName + " added to cart");
}


/*
 * Display cart items and calculate total.
 */
function displayCart() {
    var cartList =
        document.getElementById("cartList");

    var totalPrice =
        document.getElementById("totalPrice");

    if (cartList === null || totalPrice === null) {
        return;
    }

    cartList.innerHTML = "";
    total = 0;

    if (cart.length === 0) {
        var emptyItem =
            document.createElement("li");

        emptyItem.textContent =
            "Your cart is empty.";

        cartList.appendChild(emptyItem);

        totalPrice.textContent =
            "Total: $0.00";

        return;
    }

    for (var i = 0; i < cart.length; i++) {
        var subtotal =
            cart[i].price * cart[i].quantity;

        total = total + subtotal;

        var listItem =
            document.createElement("li");

        var itemText =
            document.createElement("span");

        itemText.textContent =
            cart[i].name +
            " x " +
            cart[i].quantity +
            " - $" +
            subtotal.toFixed(2);

        var removeButton =
            document.createElement("button");

        removeButton.type = "button";
        removeButton.textContent = "Remove";

        /*
         * Store the item ID on the button.
         */
        removeButton.dataset.itemId =
            String(cart[i].itemId);

        removeButton.addEventListener(
            "click",
            function () {
                removeFromCart(
                    Number(this.dataset.itemId)
                );
            }
        );

        listItem.appendChild(itemText);
        listItem.appendChild(removeButton);
        cartList.appendChild(listItem);
    }

    totalPrice.textContent =
        "Total: $" + total.toFixed(2);
}


/*
 * Remove one quantity of an item.
 * If quantity reaches zero, remove the item.
 */
function removeFromCart(itemId) {
    for (var i = 0; i < cart.length; i++) {
        if (cart[i].itemId === itemId) {
            cart[i].quantity =
                cart[i].quantity - 1;

            if (cart[i].quantity <= 0) {
                cart.splice(i, 1);
            }

            break;
        }
    }

    displayCart();
}


/*
 * Clear all cart items.
 */
function clearCart(showAlert) {
    cart = [];
    total = 0;

    displayCart();

    if (showAlert !== false) {
        alert("Cart cleared");
    }
}


/*
 * Send the cart to checkout_process.php.
 */
async function checkout() {
    if (cart.length === 0) {
        alert("Your cart is empty");
        return false;
    }

    try {
        var response = await fetch(
            "checkout_process.php",
            {
                method: "POST",

                /*
                 * Include the current PHP session cookie.
                 */
                credentials: "same-origin",

                headers: {
                    "Content-Type":
                        "application/json"
                },

                body: JSON.stringify({
                    items: cart
                })
            }
        );

        /*
         * Read as text first so that PHP errors
         * can be inspected if invalid JSON is returned.
         */
        var responseText =
            await response.text();

        var result;

        try {
            result = JSON.parse(responseText);
        } catch (jsonError) {
            console.error(
                "Invalid server response:",
                responseText
            );

            alert(
                "The checkout server returned an invalid response. " +
                "Check the PHP or Docker logs."
            );

            return false;
        }

        if (!response.ok || result.success !== true) {
            alert(
                result.message ||
                "Checkout failed"
            );

            return false;
        }

        alert(
            "Order placed successfully!\n" +
            "Order number: " +
            result.orderId +
            "\nTotal: $" +
            result.total
        );

        clearCart(false);

        return true;

    } catch (error) {
        console.error(
            "Checkout request failed:",
            error
        );

        alert(
            "Unable to connect to the checkout system"
        );

        return false;
    }
}


/*
 * Display the initial empty cart.
 */
document.addEventListener(
    "DOMContentLoaded",
    function () {
        displayCart();
    }
);