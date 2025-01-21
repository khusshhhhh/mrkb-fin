<?php
session_start();
include 'db_connection.php';

if (!isset($_GET['id'])) {
    header("Location: crm_clients.php");
    exit();
}

$id = intval($_GET['id']);

// Prepare the delete query
$stmt = $conn->prepare("DELETE FROM clients WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    header("Location: crm_clients.php?msg=Client deleted successfully");
    exit();
} else {
    header("Location: crm_clients.php?error=Error deleting client");
    exit();
}
?>