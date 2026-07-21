<?php
session_start();
include "db.php";

$email = $_POST["email"];
$password = $_POST["password"];

if ($email == "" || $password == "") {
    echo "<script>alert('Please fill in all fields'); window.location.href='login.html';</script>";
    exit();
}

$sql = "SELECT * FROM users WHERE email = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows == 1) {
    $user = $result->fetch_assoc();

    if (password_verify($password, $user["password"])) {
        $_SESSION["user_id"] = $user["user_id"];
        $_SESSION["full_name"] = $user["full_name"];
        $_SESSION["role"] = $user["role"];

        echo "<script>alert('Login successful'); window.location.href='index.php';</script>";
    } else {
        echo "<script>alert('Incorrect password'); window.location.href='login.html';</script>";
    }
} else {
    echo "<script>alert('User not found'); window.location.href='login.html';</script>";
}

$stmt->close();
$conn->close();
?>