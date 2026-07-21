<?php
$host = "db";
$username = "campus_user";
$password = "campus_pass";
$database = "smart_campus_food";

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");
?>