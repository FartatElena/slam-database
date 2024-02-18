<?php

$servername = "slam-database.c78imuwuqt5q.eu-west-2.rds.amazonaws.com";
$username = "elena";
$password = "25K27ab976EF!";
$dbname = "SLAM";

try {
    $conn = new PDO('mysql:host=' . $host . ';dbname=' . $dbname, $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Connection failed: ' . $e->getMessage());
}

// Get data from POST request
$REF = $_POST['REF'];
$STOP1 = $_POST['STOP_1'];
$STOP2 = $_POST['STOP_2'];
$ARRIVALSTOP1 = $_POST['STOP_1_ARRIVAL'];
$ARRIVALSTOP2 = $_POST['STOP_2_ARRIVAL'];
$EQUIPMENT = $_POST['EQUIPMENT'];
$DRIVER = $_POST['DRIVER'];
$TRAILERIN = $_POST['TRL_IN'];
$TRAILEROUT = $_POST['TRL_OUT'];
$NEEDEDINFO = $_POST['INFO_NEEDED'];
$ISSUES = $_POST['ISSUES'];

// SQL statement
$sql = "INSERT INTO zeus (REF, STOP_1, STOP_2, STOP_1_ARRIVAL, STOP_2_ARRIVAL, EQUIPMENT, DRIVER, TRL_IN, TRL_OUT, NEEDED_INFO, ISSUES) VALUES (:REF, :STOP_1, :STOP_2, :STOP_1_ARRIVAL, :STOP_2_ARRIVAL, :EQUIPMENT, :DRIVER, :TRL_IN, :TRL_OUT, :NEEDED_INFO, :ISSUES)";

// Prepare the statement
$stmt = $conn->prepare($sql);

// Bind variables to the statement
$stmt->bindParam(':REF', $REF);
$stmt->bindParam(':STOP_1', $STOP_1);
$stmt->bindParam(':STOP_2', $STOP_2);
$stmt->bindParam(':STOP_1_ARRIVAL', $STOP_1_ARRIVAL);
$stmt->bindParam(':STOP_2_ARRIVAL', $STOP_2_ARRIVAL);
$stmt->bindParam(':EQUIPMENT', $EQUIPMENT);
$stmt->bindParam(':DRIVER', $DRIVER);
$stmt->bindParam(':TRL_IN', $TRL_IN);
$stmt->bindParam(':TRL_OUT', $TRL_OUT);
$stmt->bindParam(':NEEDED_INFO', $NEEDED_INFO);
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
