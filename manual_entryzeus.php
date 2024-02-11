<?php

// Database connection parameters
$host = '127.0.0.1';
$dbname = 'Non Amazon';
$username = 'root';
$password = '';

// Create a PDO connection
try {
    $conn = new PDO('mysql:host=' . $host . ';dbname=' . $dbname, $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Connection failed: ' . $e->getMessage());
}

// Get data from POST request
$REF = $_POST['REF'];
$STOP1 = $_POST['STOP1'];
$STOP2 = $_POST['STOP2'];
$ARRIVALSTOP1 = $_POST['ARRIVALSTOP1'];
$ARRIVALSTOP2 = $_POST['ARRIVALSTOP2'];
$EQUIPMENT = $_POST['EQUIPMENT'];
$DRIVER = $_POST['DRIVER'];
$TRAILERIN = $_POST['TRAILERIN'];
$TRAILEROUT = $_POST['TRAILEROUT'];
$NEEDEDINFO = $_POST['NEEDEDINFO'];
$ISSUES = $_POST['ISSUES'];

// SQL statement
$sql = "INSERT INTO zeus (REF, STOP 1, STOP 2, ARRIVAL STOP 1, ARRIVAL STOP 2, EQUIPMENT, DRIVER, TRAILER IN, TRAILER OUT, NEEDED INFO, ISSUES) VALUES (:REF, :STOP1, :STOP2, :ARRIVALSTOP1, :ARRIVALSTOP2, :EQUIPMENT, :DRIVER, :TRAILERIN, :TRAILEROUT, :NEEDEDINFO, :ISSUES)";

// Prepare the statement
$stmt = $conn->prepare($sql);

// Bind variables to the statement
$stmt->bindParam(':REF', $REF);
$stmt->bindParam(':STOP1', $STOP1);
$stmt->bindParam(':STOP2', $STOP2);
$stmt->bindParam(':ARRIVALSTOP1', $ARRIVALSTOP1);
$stmt->bindParam(':ARRIVALSTOP2', $ARRIVALSTOP2);
$stmt->bindParam(':EQUIPMENT', $EQUIPMENT);
$stmt->bindParam(':DRIVER', $DRIVER);
$stmt->bindParam(':TRAILERIN', $TRAILERIN);
$stmt->bindParam(':TRAILEROUT', $TRAILEROUT);
$stmt->bindParam(':NEEDEDINFO', $NEEDEDINFO);
$stmt->bindParam(':ISSUES', $ISSUES);

// Execute the statement
if ($stmt->execute()) {
    echo "Data inserted successfully";
} else {
    echo "Failed to insert data: " . $stmt->errorInfo();
}

// Close the connection
$conn->close();

?>
