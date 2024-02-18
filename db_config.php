<?php
        $servername = "slam-database.c78imuwuqt5q.eu-west-2.rds.amazonaws.com";
        $username = "elena";
        $password = "25K27ab976EF!";
        $dbname = "SLAM";

// Create a database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

error_reporting(E_ALL);
ini_set('display_errors', 1);

?>