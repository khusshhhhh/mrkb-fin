<?php
session_start();
include 'db_connection.php';
include 'crm_header.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'] ?? null;
    $phone = $_POST['phone'] ?? null;
    $email = $_POST['email'] ?? null;
    $service = $_POST['service'] ?? null;
    $note = $_POST['note'] ?? null;

    $stmt = $conn->prepare("INSERT INTO clients (name, phone, email, service, note) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $name, $phone, $email, $service, $note);

    if ($stmt->execute()) {
        header("Location: crm_clients.php?msg=Client added successfully");
        exit();
    } else {
        $error = "Error adding client!";
    }
}
?>

<div class="container mt-4">
    <h2 class="text-center mb-4">Add New Client</h2>

    <?php if (isset($error))
        echo "<div class='alert alert-danger'>$error</div>"; ?>

    <form method="POST">
        <div class="mb-3">
            <input type="text" name="name" class="form-control" placeholder="Client Name" required>
        </div>
        <div class="mb-3">
            <input type="text" name="phone" class="form-control" placeholder="Phone">
        </div>
        <div class="mb-3">
            <input type="email" name="email" class="form-control" placeholder="Email">
        </div>
        <div class="mb-3">
            <input type="text" name="service" class="form-control" placeholder="Service">
        </div>
        <div class="mb-3">
            <textarea name="note" class="form-control" placeholder="Admin Note (Only visible to admin)"></textarea>
        </div>
        <button type="submit" class="btn btn-success">Add Client</button>
        <a href="crm_clients.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<?php include 'crm_footer.php'; ?>