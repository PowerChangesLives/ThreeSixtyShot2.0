<?php
require "../../../vendor/autoload.php";
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

// Only process POST requests.
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form fields and remove whitespace.
    $name = strip_tags(trim($_POST["app_name"]));
    $name = str_replace(array("\r", "\n"), array(" ", " "), $name);
    $email = filter_var(trim($_POST["app_email"]), FILTER_SANITIZE_EMAIL);
    $phone = strip_tags(trim($_POST["app_phone"]));
    $phone = str_replace(array("\r", "\n"), array(" ", " "), $phone);
    $request = strip_tags(trim($_POST["special_request"]), FILTER_SANITIZE_STRING);
    $type = filter_input(INPUT_POST, 'app_services', FILTER_SANITIZE_STRING);
    $date = filter_input(INPUT_POST, 'app_free_time', FILTER_SANITIZE_STRING);
    $time = filter_input(INPUT_POST, 'reservation-time', FILTER_SANITIZE_STRING);

    // Check that data was sent to the mailer.
    if (empty($name) or empty($phone) or empty($email) or empty($request) or empty($time) or empty($type)
        or empty($date)or !filter_var($email, FILTER_VALIDATE_EMAIL))  {

        // Set a 400 (bad request) response code and exit.
        http_response_code(400);
        //echo "Oops! There was a problem with your submission. Please complete the form and try again.";
        exit;
    }

    $mail = new PHPMailer(true);

    //$mail->SMTPDebug = SMTP::DEBUG_SERVER; //Deactivate this line when not debugging. It causes secret info to show in the browser.

    $mail->isSMTP();
    $mail->SMTPAuth = true;

    $mail->Host = "in-v3.mailjet.com";
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    $mail->Username = "APIKey";
    $mail->Password = "SecretKey";

    $message = "Someone used the 'contact us' form:" . "\n\n" .
        "Name: " . $name . "\n\n" .
        "Contact phone: " . $phone . "\n\n" .
        "Contact email: " . $email . "\n\n\n\n" .

        "Date: " . $date . "\n\n" .
        "Time: " . $time . "\n\n" .
        "Type: " . $type . "\n\n" .
        "Message from Contact: " . $request;

    try {
        $mail->setFrom("info@threesixtyshot.com", $name);
    } catch (\PHPMailer\PHPMailer\Exception $e) {
        // Handle the exception as needed
        echo "An error occurred. Please try again later.";
    }

    try {
        $mail->addAddress("info@threesixtyshot.com", "Contact from" . " " . $name);
    } catch (\PHPMailer\PHPMailer\Exception $e) {
        // Handle the exception as needed
        echo "An error occurred. Please try again later.";
    }

    $mail->Subject = "Someone Made a Reservation!";
    $mail->Body = $message;

    try {
        $mail->send();
    } catch (\PHPMailer\PHPMailer\Exception $e) {
        // Handle the exception as needed
        echo "An error occurred. Please try again later.";
    }

    //ob_end_flush();
    header("Location: sent.html");
    exit; // Ensure script stops execution after header redirect
}
?>
