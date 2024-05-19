<?php
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
    exit();
}

// Database connection details
$servername = 'unclaimedfinancecom.ipagemysql.com';
$username = '3sixtyshot_com';
$password = 'Jeremiah2720_';
$dbname = 'threesixtyshot_db05182024';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the email address from the form
    $email = $_POST['email'];

    var_dump("Email from form: " . $email);

    //The next three lines log the emails into a file, just in case the db doesn't work for some reason.
    // Define the file path where you want to write the variable
    $logFilePath = __DIR__ . '/logs/subscriber_log.txt';
// Format the log message
    $logMessage = $email . PHP_EOL; // PHP_EOL adds a newline for better readability
// Write the log message to the file
    file_put_contents($logFilePath, $logMessage, FILE_APPEND | LOCK_EX); // FILE_APPEND appends to the file, LOCK_EX locks the file for exclusive writing



    // Validate the email address
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die('Connection failed: ' . $conn->connect_error);
        } else {
            echo "Connected to the database successfully.<br>";
        }

        // Prepare and bind
        $stmt = $conn->prepare("INSERT INTO subscribers (email) VALUES (?)");
        if ($stmt === false) {
            die('Prepare failed: ' . $conn->error);
            var_dump("The SQL statement was not prepared, line 56. ");
        } else {
            echo "Statement prepared successfully.<br>";
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
?>
