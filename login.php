<?php
session_start(); // Start the session

// Simulated user data (replace with database query)
$users = [
    "ana-maria@slamdvd.co.uk" => password_hash("adf2341")
    "mihaip@slamdvd.co.uk" => password_hash("acasc12")
    "iulian@slamdvd.co.uk" => password_hash("scdc1123")
    "valeriu@slamdvd.co.uk" => password_hash("sdfbhjk12")
    "elena@slamdvd.co.uk" => password_hash("wqed12")
    "alexandra@slamdvd.co.uk" => password_hash("dfgds234")
];

$email = $_POST["email"];
$password = $_POST["password"];

// Check if the email exists in the users array
if (isset($users[$email])) {
    // Verify the password
    if (password_verify($password, $users[$email])) {
        // Password is correct, set the user's role in the session
        $_SESSION["user_role"] = ucfirst($email);
        header("Location: home.php"); // Redirect to the homepage
        exit;
    }
}

// Invalid credentials, redirect back to login
header("Location: login.php?error=1"); // You can add an error parameter to display an error message
exit;

error_reporting(E_ALL);
ini_set('display_errors', 1);

?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>
    <h2>Login</h2>
    <form action="process_login.php" method="post">
        <div class="form-floating mb-3">
            <input type="email" class="form-control" id="floatingInput" name="email" placeholder="name@example.com" required>
            <label for="floatingInput">Email address</label>
        </div>
        <div class="form-floating">
            <input type="password" class="form-control" id="floatingPassword" name="password" placeholder="Password" required>
            <label for="floatingPassword">Password</label>
        </div>
        <button type="submit" class="btn btn-primary">Login</button>
    </form>
</body>
</html>

