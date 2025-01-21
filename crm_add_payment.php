<?php
session_start();
include 'db_connection.php';
include 'crm_header.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $client_name = $_POST['client_name'] ?? null;
    $amount = $_POST['amount'] ?? null;
    $payment_date = $_POST['payment_date'] ?? null;
    $note = $_POST['note'] ?? null;

    // Fetch client_id if client exists, else insert a new client
    $stmt = $conn->prepare("SELECT id FROM clients WHERE name = ?");
    $stmt->bind_param("s", $client_name);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($client_id);
        $stmt->fetch();
    } else {
        // Insert new client and get the new client ID
        $insertClient = $conn->prepare("INSERT INTO clients (name) VALUES (?)");
        $insertClient->bind_param("s", $client_name);
        $insertClient->execute();
        $client_id = $insertClient->insert_id;
    }

    // Insert payment record
    $stmt = $conn->prepare("INSERT INTO payments (client_id, amount, payment_date, note) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("idss", $client_id, $amount, $payment_date, $note);

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
        <div class="mb-3">
            <input type="text" id="client_name" name="client_name" class="form-control" placeholder="Client Name"
                autocomplete="off">
            <div id="clientList" class="list-group"></div>
        </div>
        <div class="mb-3">
            <input type="number" name="amount" step="0.01" class="form-control" placeholder="Amount">
        </div>
        <div class="mb-3">
            <input type="date" name="payment_date" class="form-control">
        </div>
        <div class="mb-3">
            <textarea name="note" class="form-control" placeholder="Admin Note"></textarea>
        </div>
        <button type="submit" class="btn btn-success">Add Payment</button>
        <a href="crm_payments.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<?php include 'crm_footer.php'; ?>

<!-- jQuery and AJAX for live search -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        $('#client_name').keyup(function () {
            let query = $(this).val();
            if (query.length >= 2) {
                $.ajax({
                    url: "fetch_clients.php",
                    method: "POST",
                    data: { query: query },
                    success: function (data) {
                        $('#clientList').fadeIn();
                        $('#clientList').html(data);
                    }
                });
            } else {
                $('#clientList').fadeOut();
            }
        });

        $(document).on('click', '.client-item', function () {
            $('#client_name').val($(this).text());
            $('#clientList').fadeOut();
        });

        $(document).click(function (e) {
            if (!$(e.target).closest('#client_name, #clientList').length) {
                $('#clientList').fadeOut();
            }
        });
    });
</script>