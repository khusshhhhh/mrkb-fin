<?php
session_start();
include 'db_connection.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("DELETE FROM contact_us WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: crm_clients.php?msg=Client deleted successfully");
    } else {
        echo "Error deleting client!";
    }
} else {
    header("Location: crm_clients.php");
}
?>