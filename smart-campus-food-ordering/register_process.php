<?php
include "db.php";

$full_name = $_POST["registerName"];
$email = $_POST["registerEmail"];
$password = $_POST["registerPassword"];
$confirm_password = $_POST["confirmPassword"];

if ($full_name == "" || $email == "" || $password == "" || $confirm_password == "") {
    echo "<script>alert('Please fill in all fields'); window.location.href='register.html';</script>";
    exit();
}

if ($password != $confirm_password) {
    echo "<script>alert('Passwords do not match'); window.location.href='register.html';</script>";
    exit();
}

$hashed_password = password_hash($password, PASSWORD_DEFAULT);

$sql = "INSERT INTO users (full_name, email, password) VALUES (?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $full_name, $email, $hashed_password);

if ($stmt->execute()) {
    echo "<script>alert('Account registered successfully'); window.location.href='login.html';</script>";
} else {
    echo "<script>alert('Email already exists or registration failed'); window.location.href='register.html';</script>";
}

$stmt->close();
$conn->close();
?>