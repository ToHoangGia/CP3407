function validateLogin() {

    var email = document.forms["loginForm"]["email"].value;
    var password = document.forms["loginForm"]["password"].value;

    if (email == "") {
        alert("Email must be filled out");
        return false;
    }

    if (password == "") {
        alert("Password must be filled out");
        return false;
    }

    return true;
}


function validateRegister() {

    var name = document.forms["registerForm"]["registerName"].value;
    var email = document.forms["registerForm"]["registerEmail"].value;
    var password = document.forms["registerForm"]["registerPassword"].value;
    var confirmPassword = document.forms["registerForm"]["confirmPassword"].value;

    if (name == "") {
        alert("Full name must be filled out");
        return false;
    }

    if (email == "") {
        alert("Email must be filled out");
        return false;
    }

    if (password == "") {
        alert("Password must be filled out");
        return false;
    }

    if (confirmPassword == "") {
        alert("Please confirm your password");
        return false;
    }

    if (password != confirmPassword) {
        alert("Passwords do not match");
        return false;
    }

    return true;
}


var cart = [];
var total = 0;


function addToCart(foodName, price) {

    cart.push({
        name: foodName,
        price: price
    });

    total = total + price;

    displayCart();

    alert(foodName + " added to cart");
}


function displayCart() {

    var cartList = document.getElementById("cartList");
    var totalPrice = document.getElementById("totalPrice");

    if (cartList == null || totalPrice == null) {
        return;
    }

    cartList.innerHTML = "";

    for (var i = 0; i < cart.length; i++) {
        var item = document.createElement("li");
        item.innerHTML = cart[i].name + " - $" + cart[i].price.toFixed(2);
        cartList.appendChild(item);
    }

    totalPrice.innerHTML = "Total: $" + total.toFixed(2);
}


function clearCart() {

    cart = [];
    total = 0;

    displayCart();

    alert("Cart cleared");
}


function checkout() {

    if (cart.length == 0) {
        alert("Your cart is empty");
        return false;
    }

    alert("Order placed successfully!");

    cart = [];
    total = 0;
    displayCart();

    return true;
}