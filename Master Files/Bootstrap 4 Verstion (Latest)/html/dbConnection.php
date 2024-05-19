<?php

echo "DB file triggered";
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Handle CORS
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Authorization");

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    header("HTTP/1.1 200 OK");
    var_dump("Preflight responded 200 OK line 14");
    exit();
}

// Debug: Check if form data is received
var_dump($_POST);

// Database connection details
$servername = 'unclaimedfinancecom.ipagemysql.com';
$username = '3sixtyshot_com';
$password = "PASSWORD";
$dbname = 'threesixtyshot_db05182024';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the email address from the form
    $email = $_POST['email'];

    // Debug: Check the email received from the form
    var_dump("Email from form: " . $email);

    // Define the file path where you want to write the variable
    $logFilePath = __DIR__ . '/logs/subscriber.log';
    // Format the log message
    $logMessage = $email . PHP_EOL;
    // Write the log message to the file
    file_put_contents($logFilePath, $logMessage, FILE_APPEND | LOCK_EX);

    // Validate the email address
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die('Connection failed: ' . $conn->connect_error);
        }

        // Prepare and bind (Replace 'email_column_name' with the actual column name in your 'subscribers' table)
        $stmt = $conn->prepare("INSERT INTO subscribers (email) VALUES (?)");
        if ($stmt === false) {
            die('Prepare failed: ' . $conn->error);
        }

        $stmt->bind_param("s", $email);

        // Execute the query
        if ($stmt->execute()) {
            echo "Subscription successful!";
        } else {
            echo "Error executing statement: " . $stmt->error;
        }

        // Close the connection
        $stmt->close();
        $conn->close();
    } else {
        echo "Invalid email address!";
    }
} else {
    echo "Invalid request method!";
}

echo "DB code complete";
?>
