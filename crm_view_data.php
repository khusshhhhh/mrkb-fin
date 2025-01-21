<?php
include 'db_connection.php'; // Database connection
include 'crm_header.php'; // Include the header

// Fetch responses from contact_us table
$sql = "SELECT id, name, phone, email, state, post_code, service, message, created_date, note FROM contact_us ORDER BY created_date DESC";
$result = $conn->query($sql);
?>

<div class="container mt-5">
    <h2 class="text-center mb-4">Submitted Contact Responses</h2>

    <div class="table-responsive">
        <table class="table table-bordered table-hover bg-white">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>State</th>
                    <th>Post Code</th>
                    <th>Service</th>
                    <th>Message</th>
                    <th>Created Date</th>
                    <th>Admin Note</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['phone']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['state']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['post_code']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['service']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['message']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['created_date']) . "</td>";
                        echo "<td>
                                <form method='POST' action='crm_view_data.php'>
                                    <input type='hidden' name='id' value='" . $row['id'] . "'>
                                    <textarea name='note' class='form-control' rows='2'>" . htmlspecialchars($row['note']) . "</textarea>
                                    <button type='submit' name='update_note' class='btn btn-sm btn-primary mt-2'>Save</button>
                                </form>
                              </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='11' class='text-center'>No responses found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php
// Handle admin note update
if (isset($_POST['update_note'])) {
    $id = intval($_POST['id']);
    $note = mysqli_real_escape_string($conn, $_POST['note']);

    $update_sql = "UPDATE contact_us SET note='$note' WHERE id=$id";
    if ($conn->query($update_sql) === TRUE) {
        echo "<script>alert('Note updated successfully!'); window.location.href='crm_view_data.php';</script>";
    } else {
        echo "Error updating note: " . $conn->error;
    }
}

$conn->close();
include 'crm_footer.php'; // Include the footer
?>