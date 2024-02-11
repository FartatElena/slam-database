<?php
session_start(); // Start the session

// Check if the user is logged in
if (!isset($_SESSION["user_role"])) {
    header("Location: login.php"); // Redirect to the login page if not logged in
    exit;
}

// Get the user's role from the session
$user_role = $_SESSION["user_role"];
?>
   
   <?php 
   $title = "Home";
   include 'includes/header.php' 
   ?>


<!DOCTYPE html>
<html>
<head>
    <?php echo: "Have an easy shift" ?>
</head>

 <body>

    <nav>
        <ul>
            <li><a href="home.php">Home</a></li>
            <li>
                <a href="gatehouse.php" <?php if ($user_role == "ana") echo "disabled"; ?>>Gatehouse</a>
            </li>
            <li>
                <a href="loops.php" <?php if ($user_role == "ana" || $user_role == "alexandra") echo "disabled"; ?>>Loops</a>
            </li>
            <li>
                <a href="lists.php" <?php if ($user_role == "ana" || $user_role == "alexandra") echo "disabled"; ?>>Lists</a>
            </li>
            <!-- Add other navigation items as needed -->
        </ul>
    </nav>
</body>
</html>
 <html>

     
<?php require 'includes/footer.php' ?>

