<?php
session_start();
include 'db_connection.php';
include 'crm_header.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $client_name = $_POST['client_name'] ?? null;
    $amount = $_POST['amount'] ?? null;
    $payment_date = $_POST['payment_date'] ?? null;

    $stmt = $conn->prepare("INSERT INTO payments (client_name, amount, payment_date) VALUES (?, ?, ?)");
    $stmt->bind_param("sds", $client_name, $amount, $payment_date);

    if ($stmt->execute()) {
        header("Location: crm_payments.php?msg=Payment added successfully");
        exit();
    } else {
        $error = "Error adding payment!";
    }
}
?>

<div class="container mt-4">
    <h2 class="text-center mb-4">Add New Payment</h2>

    <?php if (isset($error))
        echo "<div class='alert alert-danger'>$error</div>"; ?>

    <form method="POST">
        <div class="mb-3"><input type="text" name="client_name" class="form-control" placeholder="Client Name"></div>
        <div class="mb-3"><input type="number" name="amount" step="0.01" class="form-control" placeholder="Amount">
        </div>
        <div class="mb-3"><input type="date" name="payment_date" class="form-control"></div>
        <button type="submit" class="btn btn-success">Add Payment</button>
        <a href="crm_payments.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<?php include 'crm_footer.php'; ?>