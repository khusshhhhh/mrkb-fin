<?php
session_start();
include 'db_connection.php';
include 'crm_header.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $amount = $_POST['amount'] ?? null;
    $date = $_POST['date'] ?? null;
    $note = $_POST['note'] ?? null;

    $stmt = $conn->prepare("INSERT INTO expenses (amount, date, note) VALUES (?, ?, ?)");
    $stmt->bind_param("dss", $amount, $date, $note);

    if ($stmt->execute()) {
        header("Location: crm_expenses.php?msg=Expense added successfully");
        exit();
    } else {
        $error = "Error adding expense!";
    }
}
?>

<div class="container mt-4">
    <h2 class="text-center mb-4">Add New Expense</h2>

    <?php if (isset($error))
        echo "<div class='alert alert-danger'>$error</div>"; ?>

    <form method="POST">
        <div class="mb-3"><input type="number" name="amount" step="0.01" class="form-control" placeholder="Amount">
        </div>
        <div class="mb-3"><input type="date" name="date" class="form-control"></div>
        <div class="mb-3"><textarea name="note" class="form-control" placeholder="Expense Note"></textarea></div>
        <button type="submit" class="btn btn-success">Add Expense</button>
        <a href="crm_expenses.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<?php include 'crm_footer.php'; ?>