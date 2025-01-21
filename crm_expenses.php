<?php
session_start();
include 'db_connection.php';
include 'crm_header.php';

// Fetch all expenses
$result = $conn->query("SELECT * FROM expenses ORDER BY id DESC");
?>

<div class="container mt-4">
    <h2 class="text-center mb-4">Expenses</h2>
    <a href="crm_add_expense.php" class="btn btn-primary mb-3">Add New Expense</a>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Amount</th>
                <th>Date</th>
                <th>Note</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?= $row['id']; ?></td>
                    <td>$<?= number_format($row['amount'], 2); ?></td>
                    <td><?= htmlspecialchars($row['date']); ?></td>
                    <td><?= htmlspecialchars($row['note']); ?></td>
                    <td>
                        <a href="crm_edit_expense.php?id=<?= $row['id']; ?>" class="btn btn-sm btn-warning">Edit</a>
                        <a href="crm_delete_expense.php?id=<?= $row['id']; ?>" class="btn btn-sm btn-danger"
                            onclick="return confirm('Are you sure?');">Delete</a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<?php include 'crm_footer.php'; ?>