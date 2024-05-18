<?php
require "../../../vendor/autoload.php";


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;


// Only process POST reqeusts.
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form fields and remove whitespace.
    $fName = strip_tags(trim($_POST["fName"]));
    $lName = strip_tags(trim($_POST["lName"]));
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $phone = strip_tags(trim($_POST["phone"]));
    $message_input = trim($_POST["message"]);

//    var_dump("Here is a list of variables from the form:");
//    var_dump("fName: ".$fName);
//    var_dump("lName: ".$lName);
//    var_dump("email: ".$email);
//    var_dump("phone: ".$phone);
//    var_dump("message_input: ".$message_input);

    // Check that data was sent to the mailer.
    if (empty($fName) or empty($lName) or empty($phone) or empty($message_input) or !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Set a 400 (bad request) response code and exit.
        http_response_code(400);
        echo "Oops! There was a problem with your submission. Please complete the form and try again.";
        exit;
    }

//var_dump("Initializing new PHP mailer at line 33");
    $mail = new PHPMailer(true);

$mail->SMTPDebug = SMTP::DEBUG_SERVER; //Deactivate this line when not debugging. It causes secret info to show in the browser.

//var_dump("Setting authentication at line 38");

    $mail->isSMTP();
    $mail->SMTPAuth = true;

    $mail->Host = "in-v3.mailjet.com";
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    $mail->Username = "APIKEY";//I removed the API key and Secret Key for security purposes.
// Get them from the document that has all the project passwords in the sharepoint.
    $mail->Password = "SecretKey";//I removed the API key and Secret Key for security purposes.
// Get them from the document that has all the project passwords in the sharepoint.

//Combine form data into the correct format to send

    $name = $fName . " " . $lName;
    $message = "Someone used the 'contact us' form:" . "\n\n\n" .
        "Name: " . $fName . " " . $lName . "\n\n" .
        "Contact phone: " . $phone . "\n\n" .
        "Contact email: " . $email . "\n\n" .
        "Message from Contact: " . $message_input;


//var_dump("Setting the sender at line 63");
//Set the sender
    try {
        $mail->setFrom("info@threesixtyshot.com", $name);
    } catch (\PHPMailer\PHPMailer\Exception $e) {
        // Log detailed error information
        $errorMessage = $e->getMessage(); // Get the error message
        $errorCode = $e->getCode(); // Get the error code
        $errorFile = $e->getFile(); // Get the file where the exception occurred
        $errorLine = $e->getLine(); // Get the line number where the exception occurred
        $errorTrace = $e->getTraceAsString(); // Get the full stack trace as a string

        // Log or display the error information
        error_log("Exception caught: $errorMessage (Code: $errorCode) in $errorFile on line $errorLine");
        error_log("Stack Trace:\n$errorTrace");

        // Handle the exception as needed
        // For example, display a user-friendly error message or redirect to an error page
        echo "An error occurred. Please try again later.";
    }

//var_dump("Setting the recipient at line 83");
//Set the recipient
    try {
        $mail->addAddress("info@threesixtyshot.com", "Contact from" . " " . $name);
    } catch (\PHPMailer\PHPMailer\Exception $e) {
        // Log detailed error information
        $errorMessage = $e->getMessage(); // Get the error message
        $errorCode = $e->getCode(); // Get the error code
        $errorFile = $e->getFile(); // Get the file where the exception occurred
        $errorLine = $e->getLine(); // Get the line number where the exception occurred
        $errorTrace = $e->getTraceAsString(); // Get the full stack trace as a string

        // Log or display the error information
        error_log("Exception caught: $errorMessage (Code: $errorCode) in $errorFile on line $errorLine");
        error_log("Stack Trace:\n$errorTrace");

        // Handle the exception as needed
        // For example, display a user-friendly error message or redirect to an error page
        echo "An error occurred. Please try again later.";
    }

    $mail->Subject = $fName . " " . $lName ." Messaged Through the 'Contact Us' Form";
    $mail->Body = $message;

//var_dump("Trying to send mail");
    try {
        $mail->send();
    } catch (\PHPMailer\PHPMailer\Exception $e) {
// Log detailed error information
        $errorMessage = $e->getMessage(); // Get the error message
        $errorCode = $e->getCode(); // Get the error code
        $errorFile = $e->getFile(); // Get the file where the exception occurred
        $errorLine = $e->getLine(); // Get the line number where the exception occurred
        $errorTrace = $e->getTraceAsString(); // Get the full stack trace as a string

        // Log or display the error information
        error_log("Exception caught: $errorMessage (Code: $errorCode) in $errorFile on line $errorLine");
        error_log("Stack Trace:\n$errorTrace");

        // Handle the exception as needed
        // For example, display a user-friendly error message or redirect to an error page
        echo "An error occurred. Please try again later.";
    }

echo "email sent";
    //header("Location: sent.html");
}

?>