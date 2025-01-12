<?php
include 'db_connection.php';

// Set timezone
date_default_timezone_set('Australia/Adelaide');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	// Get form inputs
	$name = $conn->real_escape_string($_POST['con_fname']);
	$phone = $conn->real_escape_string($_POST['con_phone']);
	$email = $conn->real_escape_string($_POST['con_email']);
	$state = $conn->real_escape_string($_POST['state_selector']);
	$post_code = $conn->real_escape_string($_POST['post_code']);
	$service = $conn->real_escape_string($_POST['service_selector']);
	$message = $conn->real_escape_string($_POST['con_message']);
	$created_date = date('Y-m-d'); // Store date only

	// Insert into database
	$sql = "INSERT INTO contact_us (name, phone, email, state, post_code, service, message, created_date) 
            VALUES ('$name', '$phone', '$email', '$state', '$post_code', '$service', '$message', '$created_date')";

	if ($conn->query($sql) === TRUE) {
		// Send email notification
		$to = "info@mrkbfinance.com.au";
		$subject = "New Contact Form Submission";
		$body = "You have received a new submission from your contact form:\n\n" .
			"Name: $name\n" .
			"Phone: $phone\n" .
			"Date: $created_date\n" .
			"Email: $email\n" .
			"State: $state\n" .
			"Post Code: $post_code\n" .
			"Service: $service\n" .
			"Message: $message\n";

		$headers = "From: no-reply@mrkbfinance.com.au";

		if (mail($to, $subject, $body, $headers)) {
			echo "Email sent successfully.";
		} else {
			echo "Failed to send email.";
		}

		// Redirect with dialog box
		echo "<script>
            alert('Thank you for your submission! We will get back to you shortly.');
            window.location.href = 'contact.html';
        </script>";
	} else {
		echo "Error: " . $conn->error;
	}
}

$conn->close();
?>