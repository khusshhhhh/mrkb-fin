<?php
session_start();
include 'db_connection.php';
include 'crm_header.php';

// Redirect to login if user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: crm_login.php");
    exit();
}

// Fetch total clients (unique emails)
$total_clients = $conn->query("SELECT COUNT(DISTINCT email) FROM clients")->fetch_row()[0];

// Fetch total revenue
$total_revenue = $conn->query("SELECT SUM(amount) FROM payments")->fetch_row()[0] ?? 0;

//Total Expenses
$total_expense = $conn->query("SELECT SUM(amount) FROM expenses")->fetch_row()[0] ?? 0;

// Get revenue data for the chart
$revenueData = $conn->query("
    SELECT DATE(payment_date) AS date, SUM(amount) AS total 
    FROM payments 
    GROUP BY DATE(payment_date) 
    ORDER BY DATE(payment_date) ASC
");

$dates = [];
$revenues = [];
while ($row = $revenueData->fetch_assoc()) {
    $dates[] = $row['date'];
    $revenues[] = $row['total'];
}
?>

<div class="container mt-4">
    <h2 class="text-center mb-4">CRM Dashboard</h2>

    <div class="row">
        <div class="col-md-4">
            <div class="card text-white bg-warning mb-3">
                <div class="card-body text-center">
                    <h5 class="card-title">Total Clients</h5>
                    <h2><?= $total_clients; ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-dark mb-3">
                <div class="card-body text-center">
                    <h5 class="card-title">Total Revenue</h5>
                    <h2>$<?= number_format($total_revenue, 2); ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-success mb-3">
                <div class="card-body text-center">
                    <h5 class="card-title">Total Expense </h5>
                    <h2>$<?= number_format($total_expense, 2); ?></h2>
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
<script>
    var ctx = document.getElementById('revenueChart').getContext('2d');
    document.getElementById('revenueChart').width = 600;  // Set width
    document.getElementById('revenueChart').height = 300; // Set height

    var revenueChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: <?php echo json_encode($dates); ?>,
            datasets: [{
                label: 'Revenue ($)',
                data: <?php echo json_encode($revenues); ?>,
                borderColor: '#4CAF50',
                backgroundColor: 'rgba(76, 175, 80, 0.2)',
                borderWidth: 2,
                pointBackgroundColor: '#4CAF50',
                pointBorderColor: '#fff',
                pointRadius: 5,
                tension: 0.4 // Smooth curve
            }]
        },
        options: {
            responsive: false, // Disable auto-resizing
            maintainAspectRatio: false, // Allows custom size
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                }
            },
            scales: {
                x: {
                    grid: {
                        display: false // Removes vertical grid lines
                    },
                    ticks: {
                        autoSkip: true,
                        maxTicksLimit: 12
                    }
                },
                y: {
                    beginAtZero: true,
                    grid: {
                        color: "rgba(200, 200, 200, 0.3)" // Light grey horizontal grid lines
                    },
                    ticks: {
                        callback: function (value) {
                            return '$' + value; // Add dollar sign to y-axis values
                        }
                    }
                }
            }
        }
    });
</script>

<?php include 'crm_footer.php'; ?>