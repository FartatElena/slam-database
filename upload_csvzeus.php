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

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if a file was uploaded without errors
    if (isset($_FILES["fileInput"]) && $_FILES["fileInput"]["error"] == UPLOAD_ERR_OK) {
        // Generate a unique filename to avoid conflicts
        $uploadDir = 'uploads/';
        $uniqueFilename = uniqid() . '_' . basename($_FILES["fileInput"]["name"]);
        $uploadFile = $uploadDir . $uniqueFilename;

        // Move the uploaded file to the server
        if (move_uploaded_file($_FILES["fileInput"]["tmp_name"], $uploadFile)) {
            // File was uploaded successfully
            // Process the CSV file
            processCSVFile($conn, $uploadFile);
        } else {
            // Failed to move the uploaded file
            echo "Failed to move the file.";
        }
    } else {
        // No file uploaded or an error occurred
        echo "File upload error.";
    }
}

// Function to process the CSV file
function processCSVFile($conn, $filePath) {
    // Open the CSV file
    $fileHandle = fopen($filePath, "r");

    // Check if the file was opened successfully
    if ($fileHandle !== false) {
        // Read and process the CSV file
        while (($data = fgetcsv($fileHandle, 1000, ",")) !== false) {
            // Insert data into the MySQL database
            $sql = "INSERT INTO zeus (REF, `STOP 1`, `STOP 2`, `ARRIVAL STOP 1`, `ARRIVAL STOP 2`, EQUIPMENT, DRIVER, `TRAILER IN`, `TRAILER OUT`, `NEEDED INFO`, ISSUES) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            // Prepare the statement
            $stmt = $conn->prepare($sql);

            // Bind parameters
            $stmt->bindParam(1, $data[0]); // REF
            $stmt->bindParam(2, $data[1]); // STOP 1
            $stmt->bindParam(3, $data[2]); // STOP 2
            $stmt->bindParam(4, $data[3]); // ARRIVAL STOP 1
            $stmt->bindParam(5, $data[4]); // ARRIVAL STOP 2
            $stmt->bindParam(6, $data[5]); // EQUIPMENT
            $stmt->bindParam(7, $data[6]); // DRIVER
            $stmt->bindParam(8, $data[7]); // TRAILER IN
            $stmt->bindParam(9, $data[8]); // TRAILER OUT
            $stmt->bindParam(10, $data[9]); // NEEDED INFO
            $stmt->bindParam(11, $data[10]); // ISSUES

            // Execute the statement
            $stmt->execute();
        }

        // Close the file handle
        fclose($fileHandle);

        // Optional: Delete the uploaded file after processing if needed
        unlink($filePath);

        // Output success message
        echo "CSV file processed and data inserted successfully.";
    } else {
        // Failed to open the file
        echo "Failed to open the CSV file.";
    }
}

// Close the connection
$conn = null;
?>
