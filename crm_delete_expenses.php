<?php
session_start();
include 'db_connection.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("DELETE FROM expenses WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: crm_expenses.php?msg=Expense deleted successfully");
    } else {
        echo "Error deleting expense!";
    }
} else {
    header("Location: crm_expenses.php");
}
?>