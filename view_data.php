<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

include 'db_connection.php';

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

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Data</title>
    <style>
        body {
            display: flex;
        }

        .sidebar {
            width: 20%;
            background-color: #f0f0f0;
            padding: 15px;
        }

        .main-content {
            width: 80%;
            padding: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #ddd;
        }

        .search-form {
            margin-bottom: 20px;
        }

        .search-form input {
            padding: 8px;
            width: 300px;
        }

        .search-form button {
            padding: 8px;
        }
    </style>
</head>

<body>
    <div class="sidebar">
        <h2>Menu</h2>
        <ul>
            <li><a href="#">Dashboard</a></li>
            <li><a href="view_data.php">View Data</a></li>
        </ul>
    </div>
    <div class="main-content">
        <h1>Submitted Responses</h1>
        <form method="GET" class="search-form">
            <input type="text" name="search" placeholder="Search by Name"
                value="<?php echo htmlspecialchars($searchQuery); ?>">
            <button type="submit">Search</button>
        </form>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>State</th>
                    <th>Service</th>
                    <th>Message</th>
                    <th>Date</th>
                    <th>Note</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['name']; ?></td>
                        <td><?php echo $row['phone']; ?></td>
                        <td><?php echo $row['email']; ?></td>
                        <td><?php echo $row['state']; ?></td>
                        <td><?php echo $row['service']; ?></td>
                        <td><?php echo $row['message']; ?></td>
                        <td><?php echo $row['created_date']; ?></td>
                        <td>
                            <form method="POST">
                                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                <input type="text" name="note" value="<?php echo $row['note']; ?>">
                                <button type="submit" name="update_note">Save</button>
                            </form>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>

</html>