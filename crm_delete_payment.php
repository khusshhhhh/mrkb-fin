<?php
session_start();
include 'db_connection.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("DELETE FROM payments WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: crm_payments.php?msg=Payment deleted successfully");
    } else {
        echo "Error deleting payment!";
    }
} else {
    header("Location: crm_payments.php");
}
?>