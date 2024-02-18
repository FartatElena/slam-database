<?php
$servername = "slam-database.c78imuwuqt5q.eu-west-2.rds.amazonaws.com";
$username = "elena";
$password = "25K27ab976EF!";
$dbname = "SLAM";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get data from the AJAX request
$ref = $_POST['ref'];
$field = $_POST['field'];
$value = $_POST['value'];

// Perform the database update for Equipment Type
$sql = "UPDATE ZEUS SET `TRL_IN` = ? WHERE `REF` = ?";
$stmt = $conn->prepare($sql);

if ($stmt === false) {
    die("Database error: " . $conn->error);
}

$stmt->bind_param('ss', $value, $ref);

if ($stmt->execute() === TRUE) {
    echo "Value updated successfully!";
    $conn->commit(); // Commit changes to the database
} else {
    echo "Error updating value: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
