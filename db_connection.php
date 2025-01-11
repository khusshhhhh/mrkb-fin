<?php
$servername = "localhost"; // Database server (usually localhost)
$username = "khush"; // Replace with your cPanel database username
$password = "Khush@3160"; // Replace with your cPanel database password
$dbname = "mrkb_client"; // Replace with your cPanel database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>