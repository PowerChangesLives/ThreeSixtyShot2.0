<?php
// Custom exception class for mail errors
class MailException extends Exception {}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize the form data
    $fName = htmlspecialchars(strip_tags(trim($_POST["fName"])), ENT_QUOTES, 'UTF-8');
    $lName = htmlspecialchars(strip_tags(trim($_POST["lName"])), ENT_QUOTES, 'UTF-8');
    $email = htmlspecialchars(filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL), ENT_QUOTES, 'UTF-8');
    $phone = htmlspecialchars(strip_tags(trim($_POST["phone"])), ENT_QUOTES, 'UTF-8');
    $message_input = htmlspecialchars(trim($_POST["message"]), ENT_QUOTES, 'UTF-8');

    $to = "info@threesixtyshot.com";

    // Check for empty fields
    if (empty($fName) || empty($lName) || empty($phone) || empty($message_input) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Set a 400 (bad request) response code and exit
        http_response_code(400);
        echo "Oops! There was a problem with your submission. Please complete all fields on the form and try again.";
        exit;
    }

    try {
        // Combine form data into the correct format to send
        $name = $fName . " " . $lName;
        $subject = $name . " Contacted You Via The Contact Form";
        $body = "Someone used the 'contact us' form:\n\n\n" .
            "Name: " . $fName . " " . $lName . "\n\n" .
            "Contact phone: " . $phone . "\n\n" .
            "Contact email: " . $email . "\n\n" .
            "Message from Contact: " . $message_input;
        $headers = "From: $email\r\n";
        $headers .= "Reply-To: $email\r\n";
        $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

        // Specify the path to sendmail
        ini_set("sendmail_path", "/usr/sbin/sendmail -t -i");

        // Send the email
        if (!mail($to, $subject, $body, $headers)) {
            throw new MailException("Failed to send email.");
        }

        // Redirect to the success page
        header("Location: sent.html");
        exit;
    } catch (MailException $e) {
        echo "Error: " . $e->getMessage();
    } catch (Exception $e) {
        echo "An unexpected error occurred: " . $e->getMessage();
    }
} else {
    echo "Invalid request.";
}
?>
