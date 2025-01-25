<?php
include 'db_connection.php';

// Fetch all contact form submissions
$sql = "SELECT * FROM contact_us ORDER BY created_date DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Contact Form Responses</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            $(".notes-field").on("change", function () {
                var id = $(this).data("id");
                var note = $(this).val();

                $.ajax({
                    url: "update_notes.php",
                    type: "POST",
                    data: { id: id, note: note },
                    success: function (response) {
                        alert("Notes updated successfully!");
                    },
                    error: function () {
                        alert("Failed to update notes.");
                    }
                });
            });
        });
    </script>
</head>

<body>

    <div class="container mt-4">
        <h2 class="mb-4 text-center">Contact Form Responses</h2>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
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
                        <th>UTM Source</th>
                        <th>Notes (Admin Only)</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo htmlspecialchars($row['name']); ?></td>
                            <td><?php echo htmlspecialchars($row['phone']); ?></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td><?php echo htmlspecialchars($row['state']); ?></td>
                            <td><?php echo htmlspecialchars($row['post_code']); ?></td>
                            <td><?php echo htmlspecialchars($row['service']); ?></td>
                            <td><?php echo nl2br(htmlspecialchars($row['message'])); ?></td>
                            <td><?php echo htmlspecialchars($row['utm_source']); ?></td>
                            <td>
                                <textarea class="form-control notes-field"
                                    data-id="<?php echo $row['id']; ?>"><?php echo htmlspecialchars($row['note']); ?></textarea>
                            </td>
                            <td><?php echo $row['created_date']; ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>

<?php $conn->close(); ?>