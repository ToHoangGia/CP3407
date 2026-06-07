function validateForm() {

    var name = document.forms["orderForm"]["name"].value;
    var food = document.forms["orderForm"]["food"].value;
    var quantity = document.forms["orderForm"]["quantity"].value;
    var consent = document.forms["orderForm"]["consent"].checked;

    if (name == "") {
        alert("Name must be filled out");
        return false;
    }

    if (food == "") {
        alert("Please select a food item");
        return false;
    }

    if (quantity == "") {
        alert("Please enter quantity");
        return false;
    }

    if (quantity <= 0) {
        alert("Quantity must be greater than 0");
        return false;
    }

    if (consent == false) {
        alert("Please confirm your order");
        return false;
    }

    alert("Order submitted successfully!");

    return true;
}