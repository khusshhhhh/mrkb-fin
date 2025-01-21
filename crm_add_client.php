<?php
session_start();
include 'db_connection.php';
include 'crm_header.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'] ?? null;
    $phone = $_POST['phone'] ?? null;
    $email = $_POST['email'] ?? null;
    $service = $_POST['service'] ?? null;
    $message = $_POST['message'] ?? null;
    $state = $_POST['state'] ?? null;
    $post_code = $_POST['post_code'] ?? null;
    $created_date = date("Y-m-d");

    $stmt = $conn->prepare("INSERT INTO contact_us (name, phone, email, service, message, state, post_code, created_date) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssss", $name, $phone, $email, $service, $message, $state, $post_code, $created_date);

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
        <div class="mb-3"><input type="text" name="name" class="form-control" placeholder="Client Name"></div>
        <div class="mb-3"><input type="text" name="phone" class="form-control" placeholder="Phone"></div>
        <div class="mb-3"><input type="email" name="email" class="form-control" placeholder="Email"></div>
        <div class="mb-3"><input type="text" name="service" class="form-control" placeholder="Service"></div>
        <div class="mb-3"><textarea name="message" class="form-control" placeholder="Message"></textarea></div>
        <div class="mb-3"><input type="text" name="state" class="form-control" placeholder="State"></div>
        <div class="mb-3"><input type="text" name="post_code" class="form-control" placeholder="Post Code"></div>
        <button type="submit" class="btn btn-success">Add Client</button>
        <a href="crm_clients.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<?php include 'crm_footer.php'; ?>