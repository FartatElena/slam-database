<?php
$servername = "STOP_1_ARRIVAL";
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

// Perform the database update for (A)DHOC/(U)PDATED
$sql = "UPDATE LOOPS SET `VEHICLE_ID` = ? WHERE `BLOCK_ID` = ?";
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
