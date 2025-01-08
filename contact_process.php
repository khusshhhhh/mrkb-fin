<?php
// Database connection settings
$servername = "localhost"; // Change if your database is hosted elsewhere
$username = "khush"; // Your database username
$password = "Khush@3160"; // Your database password
$dbname = "mrkb_trial"; // Database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $name = $conn->real_escape_string($_POST['con_fname']);
    $phone = $conn->real_escape_string($_POST['con_phone']);
    $email = $conn->real_escape_string($_POST['con_email']);
    $service = $conn->real_escape_string($_POST['service_selector']);
    $message = $conn->real_escape_string($_POST['con_message']);

    // Insert data into database
    $sql = "INSERT INTO form_responses (name, phone, email, service, message) 
            VALUES ('$name', '$phone', '$email', '$service', '$message')";

    if ($conn->query($sql) === TRUE) {
        // Success: Show dialog and redirect
        echo "<script>
                alert('Thank you for contacting us!');
                window.location.href = 'contact.html';
              </script>";
    } else {
        // Error: Show error message
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Close connection
$conn->close();
?>