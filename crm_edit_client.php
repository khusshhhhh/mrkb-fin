<?php
session_start();
include 'db_connection.php';
include 'crm_header.php';

if (!isset($_GET['id'])) {
    header("Location: crm_clients.php");
    exit();
}

$id = intval($_GET['id']);
$stmt = $conn->prepare("SELECT * FROM clients WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$client = $stmt->get_result()->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $service = $_POST['service'];
    $note = $_POST['note'];

    $stmt = $conn->prepare("UPDATE clients SET name=?, phone=?, email=?, service=?, note=? WHERE id=?");
    $stmt->bind_param("sssssi", $name, $phone, $email, $service, $note, $id);

    if ($stmt->execute()) {
        header("Location: crm_clients.php?msg=Client updated successfully");
        exit();
    } else {
        $error = "Error updating client!";
    }
}
?>

<div class="container mt-4">
    <h2 class="text-center mb-4">Edit Client</h2>

    <?php if (isset($error))
        echo "<div class='alert alert-danger'>$error</div>"; ?>

    <form method="POST">
        <div class="mb-3">
            <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($client['name']); ?>"
                required>
        </div>
        <div class="mb-3">
            <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($client['phone']); ?>">
        </div>
        <div class="mb-3">
            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($client['email']); ?>">
        </div>
        <div class="mb-3">
            <input type="text" name="service" class="form-control" value="<?= htmlspecialchars($client['service']); ?>">
        </div>
        <div class="mb-3">
            <textarea name="note" class="form-control"><?= htmlspecialchars($client['note']); ?></textarea>
        </div>
        <button type="submit" class="btn btn-success">Update Client</button>
        <a href="crm_clients.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<?php include 'crm_footer.php'; ?>