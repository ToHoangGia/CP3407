<?php
session_start();

require_once __DIR__ . "/db.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: login.html");
    exit();
}

$email = trim($_POST["email"] ?? "");
$password = $_POST["password"] ?? "";

if ($email === "" || $password === "") {
    echo "
        <script>
            alert('Please fill in all fields');
            window.location.href='login.html';
        </script>
    ";
    exit();
}

$sql = "
    SELECT
        user_id,
        full_name,
        email,
        password,
        role
    FROM users
    WHERE email = ?
";

$stmt = $conn->prepare($sql);

if ($stmt === false) {
    die("Unable to prepare login request.");
}

$stmt->bind_param("s", $email);

if (!$stmt->execute()) {
    die("Unable to process login request.");
}

$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();

    if (password_verify($password, $user["password"])) {
        session_regenerate_id(true);

        $_SESSION["user_id"] = (int) $user["user_id"];
        $_SESSION["full_name"] = $user["full_name"];
        $_SESSION["role"] = $user["role"];

        echo "
            <script>
                alert('Login successful');
                window.location.href='index.php';
            </script>
        ";
    } else {
        echo "
            <script>
                alert('Incorrect password');
                window.location.href='login.html';
            </script>
        ";
    }
} else {
    echo "
        <script>
            alert('User not found');
            window.location.href='login.html';
        </script>
    ";
}

$stmt->close();
$conn->close();
?>