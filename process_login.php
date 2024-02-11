<?php
session_start(); // Start the session

// Include the database configuration
include 'db_config.php';

$email = $_POST["email"];
$password = $_POST["password"];

// Prepare and execute a SQL query to fetch user data
$stmt = $conn->prepare("SELECT email, password FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows == 1) {
    $stmt->bind_result($db_email, $db_password);
    $stmt->fetch();

    // Verify the hashed password
    if (password_verify($password, $db_password)) {
        // Password is correct, set the user's role in the session
        $_SESSION["user_role"] = ucfirst($email);
        header("Location: index.php"); // Redirect to the homepage
        exit;
    }
}

// Invalid credentials, redirect back to login
header("Location: login.php?error=1"); // You can add an error parameter to display an error message
exit;


error_reporting(E_ALL);
ini_set('display_errors', 1);

?>





