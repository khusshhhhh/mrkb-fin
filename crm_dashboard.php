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

// Fetch total revenue
$total_revenue = $conn->query("SELECT SUM(amount) FROM payments")->fetch_row()[0] ?? 0;

// Fetch monthly revenue data for the chart
$monthly_revenue = array_fill(0, 12, 0); // Initialize array with 12 months (0 values)

$stmt = $conn->prepare("SELECT MONTH(payment_date) AS month, SUM(amount) AS revenue FROM payments GROUP BY month");
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $monthly_revenue[$row['month'] - 1] = $row['revenue']; // Month index in array (0-based)
}

$chart_revenue = json_encode($monthly_revenue);
?>

<div class="container mt-4">
    <h2 class="text-center mb-4">CRM Dashboard</h2>

    <div class="row">
        <div class="col-md-3">
            <div class="card text-white bg-primary mb-3">
                <div class="card-body text-center">
                    <h5 class="card-title">Total Contacts</h5>
                    <h2><?= $total_contacts; ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success mb-3">
                <div class="card-body text-center">
                    <h5 class="card-title">Total Messages</h5>
                    <h2><?= $total_messages; ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-warning mb-3">
                <div class="card-body text-center">
                    <h5 class="card-title">Total Clients</h5>
                    <h2><?= $total_clients; ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-dark mb-3">
                <div class="card-body text-center">
                    <h5 class="card-title">Total Revenue</h5>
                    <h2>$<?= number_format($total_revenue, 2); ?></h2>
                </div>
            </div>
        </div>
    </div>

    <div class="card p-4 mt-4">
        <h4 class="text-center">Monthly Revenue Over Time</h4>
        <canvas id="revenueChart"></canvas>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<!-- <script>
    var ctx = document.getElementById('revenueChart').getContext('2d');
    document.getElementById('revenueChart').width = 600;  // Set width
    document.getElementById('revenueChart').height = 300; // Set height

    var revenueChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
            datasets: [{
                label: 'Revenue ($)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 2,
                data: <?= $chart_revenue; ?>,
                fill: true
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
</script> -->

<?php include 'crm_footer.php'; ?>