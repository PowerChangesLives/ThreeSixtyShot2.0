<?php
// Custom exception class for mail errors
class MailException extends Exception {}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize the form data
    $name = htmlspecialchars(strip_tags(trim($_POST["app_name"])), ENT_QUOTES, 'UTF-8');
    $email = htmlspecialchars(filter_var(trim($_POST["app_email"]), FILTER_SANITIZE_EMAIL), ENT_QUOTES, 'UTF-8');
    $phone = htmlspecialchars(strip_tags(trim($_POST["app_phone"])), ENT_QUOTES, 'UTF-8');
    $request = htmlspecialchars(trim($_POST["special_request"]), ENT_QUOTES, 'UTF-8');
    $type = htmlspecialchars(filter_input(INPUT_POST, 'app_services', FILTER_SANITIZE_STRING), ENT_QUOTES, 'UTF-8');
    $date = htmlspecialchars(filter_input(INPUT_POST, 'app_free_time', FILTER_SANITIZE_STRING), ENT_QUOTES, 'UTF-8');
    $time = htmlspecialchars(filter_input(INPUT_POST, 'reservation-time', FILTER_SANITIZE_STRING), ENT_QUOTES, 'UTF-8');

    // Check that data was sent to the mailer
    if (empty($name) || empty($phone) || empty($email) || empty($request) || empty($time) || empty($type) || empty($date) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Set a 400 (bad request) response code and exit
        http_response_code(400);
        echo "Oops! There was a problem with your submission. Please complete the form and try again.";
        exit;
    }

    // Combine form data into the correct format to send
    $subject = "Someone Made a Reservation!";
    $body = "Someone used the 'reservations' form:\n\n" .
        "Name: " . $name . "\n\n" .
        "Contact phone: " . $phone . "\n\n" .
        "Contact email: " . $email . "\n\n" .
        "Date: " . $date . "\n\n" .
        "Time: " . $time . "\n\n" .
        "Type: " . $type . "\n\n" .
        "Message from Contact: " . $request;
    $headers = "From: $email\r\n";
    $headers .= "Reply-To: $email\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

    // Specify the path to sendmail
    ini_set("sendmail_path", "/usr/sbin/sendmail -t -i");

    try {
        // Send the email
        if (!mail("orders@threesixtyshot.com", $subject, $body, $headers)) {
            throw new MailException("Failed to send email.");
        }

        // Redirect to the success page
        header("Location: sent.html");
        exit; // Ensure script stops execution after header redirect
    }
    catch (MailException $e) {
        echo "An error occurred. Please try again later.";
    } catch (Exception $e) {
        echo "An unexpected error occurred: " . $e->getMessage();
    }
} else {
    echo "Invalid request.";
}
?>
