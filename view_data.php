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
    <title>MRKB Admin Dashboard</title>
    <link rel="shortcut icon" href="./img/favicon.png" type="image/x-icon">
    <style>
        @import url("https://fonts.googleapis.com/css?family=Montserrat:200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i");

        body {
            display: flex;
            font-family: "Montserrat", sans-serif;
            margin: 0;
        }

        .sidebar {
            width: 15%;
            background-color: #ff9d00;
            padding: 15px;
            height: auto;
        }

        .sidebar h2 {
            color: #fff;
            margin-bottom: 40px;
        }

        .sidebar ul {
            list-style-type: none;
            padding: 0;
        }

        .sidebar ul li {
            margin-bottom: 40px;
        }

        .sidebar ul li a {
            color: #fff;
            font-weight: 500;
            text-decoration: none;
            padding: 10px;
            font-size: 22px;
            border: 2px solid #fff;
            border-radius: 4px;
            transition: all 0.4s;
        }

        .sidebar ul li a:hover {
            background-color: #fff;
            color: #ff9d00;
        }

        .main-content {
            width: 85%;
            padding: 20px;
        }

        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid black;
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #ff9d00;
            color: white;
        }

        .note-field {
            min-height: 80px;
            width: 80%;
            max-height: 200px;
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 5px;
            font-size: 14px;
            resize: vertical;
            overflow-wrap: break-word;
            word-wrap: break-word;
        }

        .message-field {
            resize: vertical;
            overflow-wrap: break-word;
            word-wrap: break-word;
        }

        td form button {
            padding: 5px;
            width: 20%;
            font-size: 14px;
            border: 1px solid #ccc;
            background-color: #00c50a;
            color: #fff;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.4s;
        }

        td form {
            display: flex;
            flex-direction: row;
            gap: 5px;
        }

        message-column {
            width: 120px;
            /* Set the desired width */
        }

        table th:nth-child(9),
        table td:nth-child(9) {
            width: 250px;
            /* Set the desired width */
        }

        button:hover {
            background-color: #008311;
        }

        .search-form {
            margin-bottom: 20px;
        }

        .search-form input {
            padding: 12px;
            width: 400px;
            font-size: 16px;
            border-radius: 8px;
            border: 1px solid #000;
        }

        .search-form button {
            padding: 12px;
            margin-left: 10px;
            font-size: 16px;
            font-weight: 600;
            border-radius: 8px;
            border: 1px solid #000;
            cursor: pointer;
            transition: all 0.4s;
        }

        .search-form button:hover {
            background-color: #ff9d00;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .sidebar {
                width: 25%;
                height: 100vh;
            }

            .sidebar ul li a {
                font-size: 12px;
                padding: 8px;
            }

            .main-content {
                width: 75%;
            }

            table {
                font-size: 14px;
            }

            table th:nth-child(9),
            table td:nth-child(9) {
                width: 250px;
                /* Set the desired width */
            }

            .search-form input {
                width: 300px;
            }

            .note-field {
                width: 100%;
            }

            td form {
                display: flex;
                flex-direction: column;
                gap: 5px;
            }

            td form button {
                width: 100%;
            }
        }

        @media (max-width: 480px) {
            body {
                flex-direction: column;
                width: 90%;
            }

            .sidebar {
                width: 100%;
                height: auto;
            }

            .main-content {
                width: 100%;
            }

            table {
                font-size: 12px;
            }

            th,
            td {
                font-size: 12px;
                padding: 8px;
            }

            table {
                display: block;
                overflow-x: auto;
            }

            table th:nth-child(9),
            table td:nth-child(9) {
                width: 250px;
                /* Set the desired width */
            }

            .search-form input {
                width: 90%;
                margin-bottom: 10px;
            }

            .search-form button {
                width: 90%;
                margin-left: 0;
            }

            .note-field {
                width: 100%;
            }

            td form button {
                width: 100%;
            }

            td form {
                display: flex;
                flex-direction: column;
                gap: 5px;
            }
        }
    </style>
</head>

<body>
    <div class="sidebar">
        <h2>MRKB Finance</h2>
        <ul>
            <li><a href="index.html">Website</a></li>
            <li><a href="view_data.php">View Data</a></li>
            <li><a href="#">Report</a></li>
        </ul>
    </div>
    <div class="main-content">
        <h1>Welcome, MRKB!</h1>
        <form method="GET" class="search-form">
            <input type="text" name="search" placeholder="Search by Name"
                value="<?php echo htmlspecialchars($searchQuery); ?>">
            <button type="submit">Search</button>
        </form>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Date</th>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>State</th>
                    <th>Service</th>
                    <th class="message-column">Message</th>
                    <th>Note</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td>
                            <?php echo $row['id']; ?>
                        </td>
                        <td>
                            <?php echo $row['created_date']; ?>
                        </td>
                        <td>
                            <?php echo $row['name']; ?>
                        </td>
                        <td>
                            <?php echo $row['phone']; ?>
                        </td>
                        <td>
                            <?php echo $row['email']; ?>
                        </td>
                        <td>
                            <?php echo $row['state']; ?>
                        </td>
                        <td>
                            <?php echo $row['service']; ?>
                        </td>
                        <td class="message-column message-field">
                            <?php echo $row['message']; ?>
                        </td>
                        <td>
                            <form method="POST">
                                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                <textarea class="note-field" name="note"><?php echo $row['note']; ?></textarea>
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