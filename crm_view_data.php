<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: crm_login.php");
    exit;
}

include 'db_connection.php';
include 'crm_header.php';

// Handle search
$searchQuery = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';

// Handle note update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_note'])) {
    $id = intval($_POST['id']);
    $note = $conn->real_escape_string($_POST['note']);
    $conn->query("UPDATE contact_us SET note='$note' WHERE id=$id");
}

// Fetch data
$sql = "SELECT * FROM contact_us";
if (!empty($searchQuery)) {
    $sql .= " WHERE name LIKE '%$searchQuery%'";
}
$result = $conn->query($sql);
?>

<div class="container mt-5">
    <h2 class="mb-4">Contact Submissions</h2>

    <!-- Search Form -->
    <form method="GET" class="d-flex mb-4">
        <input type="text" name="search" class="form-control me-2" placeholder="Search by Name"
            value="<?php echo htmlspecialchars($searchQuery); ?>">
        <button type="submit" class="btn btn-primary">Search</button>
    </form>

    <!-- Contact Data Table -->
    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Date</th>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>State</th>
                    <th>Service</th>
                    <th>Message</th>
                    <th>Note</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['created_date']; ?></td>
                        <td><?php echo $row['name']; ?></td>
                        <td><?php echo $row['phone']; ?></td>
                        <td><?php echo $row['email']; ?></td>
                        <td><?php echo $row['state']; ?></td>
                        <td><?php echo $row['service']; ?></td>
                        <td><?php echo $row['message']; ?></td>
                        <td>
                            <form method="POST" class="d-flex">
                                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                <textarea class="form-control me-2" name="note"><?php echo $row['note']; ?></textarea>
                                <button type="submit" name="update_note" class="btn btn-success">Save</button>
                            </form>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'crm_footer.php'; ?>