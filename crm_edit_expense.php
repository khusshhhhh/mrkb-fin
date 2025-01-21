<?php
session_start();
include 'db_connection.php';
include 'crm_header.php';

if (!isset($_GET['id'])) {
    header("Location: crm_expenses.php");
    exit();
}

$id = intval($_GET['id']);
$stmt = $conn->prepare("SELECT * FROM expenses WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$expense = $stmt->get_result()->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $amount = $_POST['amount'];
    $date = $_POST['date'];
    $note = $_POST['note'];

    $stmt = $conn->prepare("UPDATE expenses SET amount=?, date=?, note=? WHERE id=?");
    $stmt->bind_param("dssi", $amount, $date, $note, $id);

    if ($stmt->execute()) {
        header("Location: crm_expenses.php?msg=Expense updated successfully");
        exit();
    } else {
        $error = "Error updating expense!";
    }
}
?>

<div class="container mt-4">
    <h2 class="text-center mb-4">Edit Expense</h2>

    <?php if (isset($error))
        echo "<div class='alert alert-danger'>$error</div>"; ?>

    <form method="POST">
        <div class="mb-3"><input type="number" name="amount" step="0.01" class="form-control"
                value="<?= htmlspecialchars($expense['amount']); ?>"></div>
        <div class="mb-3"><input type="date" name="date" class="form-control"
                value="<?= htmlspecialchars($expense['date']); ?>"></div>
        <div class="mb-3"><textarea name="note"
                class="form-control"><?= htmlspecialchars($expense['note']); ?></textarea></div>
        <button type="submit" class="btn btn-success">Update Expense</button>
        <a href="crm_expenses.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<?php include 'crm_footer.php'; ?>