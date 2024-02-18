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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_FILES["fileInput"]) && $_FILES["fileInput"]["error"] == UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/';
        $uniqueFilename = uniqid() . '_' . basename($_FILES["fileInput"]["name"]);
        $uploadFile = $uploadDir . $uniqueFilename;

        if (move_uploaded_file($_FILES["fileInput"]["tmp_name"], $uploadFile)) {
            processCSVFile($conn, $uploadFile);
        } else {
            echo "Failed to move the file.";
        }
    } else {
        echo "File upload error.";
    }
}

function processCSVFile($conn, $filePath) {
    $fileHandle = fopen($filePath, "r");

    if ($fileHandle !== false) {
        while (($data = fgetcsv($fileHandle, 1000, ",")) !== false) {
            $sql = "INSERT INTO ZEUS (REF, `STOP _1`, `STOP_2`, `STOP_1_ARRIVAL`, `STOP_2_ARRIVAL`, EQUIPMENT, DRIVER, `TRL_IN`, `TRL_OUT`, `NEEDED_INFO`, ISSUES) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            $stmt = $conn->prepare($sql);

            $stmt->bindParam(1, $data[0]); // REF
            $stmt->bindParam(2, $data[1]); // STOP_1
            $stmt->bindParam(3, $data[2]); // STOP_2
            $stmt->bindParam(4, $data[3]); // STOP_1_ARRIVAL
            $stmt->bindParam(5, $data[4]); // STOP_2_ARRIVAL
            $stmt->bindParam(6, $data[5]); // EQUIPMENT
            $stmt->bindParam(7, $data[6]); // DRIVER
            $stmt->bindParam(8, $data[7]); // TRL_IN
            $stmt->bindParam(9, $data[8]); // TRL_OUT
            $stmt->bindParam(10, $data[9]); // NEEDED_INFO
            $stmt->bindParam(11, $data[10]); // ISSUES

            $stmt->execute();
        }

        fclose($fileHandle);

        unlink($filePath);

        echo "CSV file processed and data inserted successfully.";
    } else {
        echo "Failed to open the CSV file.";
    }
}

$conn = null;
?>
