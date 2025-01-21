<?php
session_start();
include 'db_connection.php';
include 'crm_header.php';

if (!isset($_GET['id'])) {
    header("Location: crm_payments.php");
    exit();
}

$id = intval($_GET['id']);

// Fetch payment details
$stmt = $conn->prepare("
    SELECT payments.*, clients.name AS client_name
    FROM payments
    LEFT JOIN clients ON payments.client_id = clients.id
    WHERE payments.id = ?
");
$stmt->bind_param("i", $id);
$stmt->execute();
$payment = $stmt->get_result()->fetch_assoc();

// Fetch clients for dropdown
$clients = $conn->query("SELECT id, name FROM clients ORDER BY name ASC");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $client_id = $_POST['client_id'];
    $amount = $_POST['amount'];
    $payment_date = $_POST['payment_date'];
    $note = $_POST['note'];

    $stmt = $conn->prepare("UPDATE payments SET client_id=?, amount=?, payment_date=?, note=? WHERE id=?");
    $stmt->bind_param("idssi", $client_id, $amount, $payment_date, $note, $id);

    if ($stmt->execute()) {
        header("Location: crm_payments.php?msg=Payment updated successfully");
        exit();
    } else {
        $error = "Error updating payment!";
    }
}
?>

<div class="container mt-4">
    <h2 class="text-center mb-4">Edit Payment</h2>

    <?php if (isset($error))
        echo "<div class='alert alert-danger'>$error</div>"; ?>

    <form method="POST">
        <div class="mb-3">
            <label for="client_id" class="form-label">Client Name</label>
            <select name="client_id" class="form-control" required>
                <?php while ($row = $clients->fetch_assoc()) { ?>
                    <option value="<?= $row['id']; ?>" <?= ($row['id'] == $payment['client_id']) ? 'selected' : ''; ?>>
                        <?= htmlspecialchars($row['name']); ?>
                    </option>
                <?php } ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="amount" class="form-label">Amount</label>
            <input type="number" name="amount" step="0.01" class="form-control"
                value="<?= htmlspecialchars($payment['amount']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="payment_date" class="form-label">Payment Date</label>
            <input type="date" name="payment_date" class="form-control"
                value="<?= htmlspecialchars($payment['payment_date']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="note" class="form-label">Note</label>
            <textarea name="note" class="form-control"><?= htmlspecialchars($payment['note']); ?></textarea>
        </div>
        <button type="submit" class="btn btn-success">Update Payment</button>
        <a href="crm_payments.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<?php include 'crm_footer.php'; ?>