<?php
session_start();
include 'db_connection.php';
include 'crm_header.php';

// Fetch all clients
$result = $conn->query("SELECT * FROM contact_us ORDER BY id DESC");
?>

<div class="container mt-4">
    <h2 class="text-center mb-4">Clients</h2>
    <a href="crm_add_client.php" class="btn btn-primary mb-3">Add New Client</a>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Phone</th>
                <th>Email</th>
                <th>Service</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?= $row['id']; ?></td>
                    <td><?= htmlspecialchars($row['name']); ?></td>
                    <td><?= htmlspecialchars($row['phone']); ?></td>
                    <td><?= htmlspecialchars($row['email']); ?></td>
                    <td><?= htmlspecialchars($row['service']); ?></td>
                    <td>
                        <a href="crm_edit_client.php?id=<?= $row['id']; ?>" class="btn btn-sm btn-warning">Edit</a>
                        <a href="crm_delete_client.php?id=<?= $row['id']; ?>" class="btn btn-sm btn-danger"
                            onclick="return confirm('Are you sure?');">Delete</a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<?php include 'crm_footer.php'; ?>