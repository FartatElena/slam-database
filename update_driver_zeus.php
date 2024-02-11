<?php
$servername = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "Non Amazon";

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
$sql = "UPDATE zeus SET `DRIVER` = ? WHERE `REF` = ?";
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
