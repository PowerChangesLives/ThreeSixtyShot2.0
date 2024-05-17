<?php
    // Only process POST reqeusts.
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Get the form fields and remove whitespace.
        $name = strip_tags(trim($_POST["app_name"]));
		$name = str_replace(array("\r","\n"),array(" "," "),$name);
        $email = filter_var(trim($_POST["app_email"]), FILTER_SANITIZE_EMAIL);
		$phone = strip_tags(trim($_POST["app_phone"]));
		$phone = str_replace(array("\r","\n"),array(" "," "),$phone);
		$app_free_time = strip_tags(trim($_POST["app_free_time"]));
		$app_free_time = str_replace(array("\r","\n"),array(" "," "),$app_free_time);
		$app_services = strip_tags(trim($_POST["app_services"]));
		$app_services = str_replace(array("\r","\n"),array(" "," "),$app_services);
		$app_barbers = strip_tags(trim($_POST["app_barbers"]));
		$app_barbers = str_replace(array("\r","\n"),array(" "," "),$app_barbers);

        // Check that data was sent to the mailer.
        if ( empty($name) OR empty($phone) OR !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            // Set a 400 (bad request) response code and exit.
            http_response_code(400);
            echo "Oops! There was a problem with your submission. Please complete the form and try again.";
            exit;
        }

        // Update this to your desired email address.
        $recipient = "info@wowthemez.com";
		$subject = "Appointment completed!";

        // Email content.
        $email_content = "Name: $name\n";
        $email_content .= "Email: $email\n";
        $email_content .= "Phone: $phone\n\n";
        $email_content .= "Free Time: $app_free_time\n\n\n";
        $email_content .= "Services: $app_services\n\n\n\n";
        $email_content .= "Barber: $app_barbers\n\n\n\n\n\n";

        // Email headers.
        $email_headers = "From: $name <$email>\r\nReply-to: <$email>";

        // Send the email.
        if (mail($recipient, $subject, $email_content, $email_headers)) {
            // Set a 200 (okay) response code.
            http_response_code(200);
            echo "Thank You! Your appointment has been completed.";
        } else {
            // Set a 500 (internal server error) response code.
            http_response_code(500);
            echo "Oops! Something went wrong and we couldn't complete appointment.";
        }

    } else {
        // Not a POST request, set a 403 (forbidden) response code.
        http_response_code(403);
        echo "There was a problem with your submission, please try again.";
    }