<?php
session_start();
include 'db_connection.php';
include 'crm_header.php';

// Redirect to login if user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: crm_login.php");
    exit();
}

// Fetch total contacts
$total_contacts = $conn->query("SELECT COUNT(*) FROM contact_us")->fetch_row()[0];

// Fetch total messages
$total_messages = $conn->query("SELECT COUNT(DISTINCT message) FROM contact_us")->fetch_row()[0];

// Fetch total clients (unique emails)
$total_clients = $conn->query("SELECT COUNT(DISTINCT email) FROM contact_us")->fetch_row()[0];

// Fetch monthly contact data for the chart
$monthly_data = array_fill(0, 12, 0); // Initialize array with 12 months (0 values)

$stmt = $conn->prepare("SELECT MONTH(created_date) AS month, COUNT(*) FROM contact_us GROUP BY month");
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $monthly_data[$row['month'] - 1] = $row['COUNT(*)']; // Month index in array (0-based)
}

$chart_data = json_encode($monthly_data);
?>

<div class="container mt-4">
    <h2 class="text-center mb-4">CRM Dashboard</h2>

    <div class="row">
        <div class="col-md-4">
            <div class="card text-white bg-primary mb-3">
                <div class="card-body text-center">
                    <h5 class="card-title">Total Contacts</h5>
                    <h2><?= $total_contacts; ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-success mb-3">
                <div class="card-body text-center">
                    <h5 class="card-title">Total Messages</h5>
                    <h2><?= $total_messages; ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-warning mb-3">
                <div class="card-body text-center">
                    <h5 class="card-title">Total Clients</h5>
                    <h2><?= $total_clients; ?></h2>
                </div>
            </div>
        </div>
    </div>

    <div class="card p-4 mt-4">
        <h4 class="text-center">Monthly Contact Form Submissions</h4>
        <canvas id="contactChart"></canvas>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    var ctx = document.getElementById('contactChart').getContext('2d');
    var chart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
            datasets: [{
                label: 'Number of Contacts',
                backgroundColor: 'rgba(54, 162, 235, 0.5)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1,
                data: <?= $chart_data; ?>
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
</script>

<?php include 'crm_footer.php'; ?>