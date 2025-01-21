<?php
session_start();
include 'db_connection.php';
include 'crm_header.php';

if (!isset($_GET['id'])) {
    header("Location: crm_payments.php");
    exit();
}

$id = intval($_GET['id']);
$stmt = $conn->prepare("SELECT * FROM payments WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$payment = $stmt->get_result()->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $client_name = $_POST['client_name'];
    $amount = $_POST['amount'];
    $payment_date = $_POST['payment_date'];

    $stmt = $conn->prepare("UPDATE payments SET client_name=?, amount=?, payment_date=? WHERE id=?");
    $stmt->bind_param("sdsi", $client_name, $amount, $payment_date, $id);

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
        <div class="mb-3"><input type="text" name="client_name" class="form-control"
                value="<?= htmlspecialchars($payment['client_name']); ?>"></div>
        <div class="mb-3"><input type="number" name="amount" step="0.01" class="form-control"
                value="<?= htmlspecialchars($payment['amount']); ?>"></div>
        <div class="mb-3"><input type="date" name="payment_date" class="form-control"
                value="<?= htmlspecialchars($payment['payment_date']); ?>"></div>
        <button type="submit" class="btn btn-success">Update Payment</button>
        <a href="crm_payments.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<?php include 'crm_footer.php'; ?>