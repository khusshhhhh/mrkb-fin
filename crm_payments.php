<?php
session_start();
include 'db_connection.php';
include 'crm_header.php';

// Fetch all payments with client names
$query = "
    SELECT payments.id, clients.name AS client_name, payments.amount, payments.payment_date, payments.note
    FROM payments
    LEFT JOIN clients ON payments.client_id = clients.id
    ORDER BY payments.id DESC
";
$result = $conn->query($query);
?>

<div class="container mt-4">
    <h2 class="text-center mb-4">Payments</h2>
    <a href="crm_add_payment.php" class="btn btn-primary mb-3">Add New Payment</a>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Client Name</th>
                <th>Amount</th>
                <th>Payment Date</th>
                <th>Note</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?= $row['id']; ?></td>
                    <td><?= htmlspecialchars($row['client_name'] ?? 'Unknown'); ?></td>
                    <td>$<?= number_format($row['amount'], 2); ?></td>
                    <td><?= htmlspecialchars($row['payment_date']); ?></td>
                    <td><?= htmlspecialchars($row['note']); ?></td>
                    <td>
                        <a href="crm_edit_payment.php?id=<?= $row['id']; ?>" class="btn btn-sm btn-warning">Edit</a>
                        <a href="crm_delete_payment.php?id=<?= $row['id']; ?>" class="btn btn-sm btn-danger"
                            onclick="return confirm('Are you sure?');">Delete</a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<?php include 'crm_footer.php'; ?>