<?php
include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $conn->real_escape_string($_POST['id']);
    $note = $conn->real_escape_string($_POST['note']);

    $sql = "UPDATE contact_us SET note = '$note' WHERE id = '$id'";

    if ($conn->query($sql) === TRUE) {
        echo "Notes updated successfully.";
    } else {
        echo "Error updating notes: " . $conn->error;
    }
}

$conn->close();
?>