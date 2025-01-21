<?php
include 'db_connection.php';

if (isset($_POST['query'])) {
    $query = $_POST['query'];
    $stmt = $conn->prepare("SELECT name FROM clients WHERE name LIKE ? LIMIT 10");
    $searchQuery = "%" . $query . "%";
    $stmt->bind_param("s", $searchQuery);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo '<a href="#" class="list-group-item list-group-item-action client-item">' . htmlspecialchars($row['name']) . '</a>';
        }
    } else {
        echo '<p class="list-group-item">No clients found</p>';
    }
}
?>