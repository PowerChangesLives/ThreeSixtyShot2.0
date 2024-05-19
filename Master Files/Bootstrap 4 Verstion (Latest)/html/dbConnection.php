<?php
// Database connection details
$host = 'unclaimedfinancecom.ipagemysql.com';
//$port = 3306;
$user = '3sixtyshot_com';
$password = 'PASSWORD'; // replace with your actual root password
$dbname = 'threesixtyshot_db05182024';

// Create connection
$conn = new mysqli($host, $user, $password, $dbname);//PORT WENT IN THE COSTRUCTOR FOR LOCAL TESTING.

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize the email from POST request
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

    // D
    $logFilePath = __DIR__ . '/logs/subscriber.log';
    // Format the log message
    $logMessage = $email . PHP_EOL;
    // Write the log message to the file
    file_put_contents($logFilePath, $logMessage, FILE_APPEND | LOCK_EX);

    // Validate the email
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // SQL query to insert data
        $sql = "INSERT INTO subscribers (emails) VALUES ('$email')";

        // Execute the query
        if ($conn->query($sql) === TRUE) {
            //echo "New record created successfully";
            header('Location: sent.html');
            exit;
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        echo "Invalid email format";
    }

    header();
}

// Close the connection
$conn->close();
?>
