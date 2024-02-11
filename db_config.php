<?php
$servername = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "Non Amazon";

// Create a database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

error_reporting(E_ALL);
ini_set('display_errors', 1);

?>