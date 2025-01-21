<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: crm_login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MRKB | CRM</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="crm_dashboard.php">CRM System</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link btn btn-outline-info" href="crm_dashboard.php">Dashboard</a>
                    </li>
                    <li class="nav-item"><a class="nav-link btn btn-outline-info" href="crm_clients.php">Clients</a>
                    </li>
                    <li class="nav-item"><a class="nav-link btn btn-outline-info" href="crm_payments.php">Payments</a>
                    </li>
                    <li class="nav-item"><a class="nav-link btn btn-outline-info" href="crm_expenses.php">Expenses</a>
                    </li>
                    <li class="nav-item"><a class="nav-link btn btn-outline-info" href="crm_expenses.php">Contect
                            From</a></li>
                    <!-- <li class="nav-item"><a class="nav-link" href="crm_tasks.php">Tasks</a></li> -->
                </ul>
                <a href="crm_logout.php" class="btn btn-danger">Logout</a>
            </div>
        </div>
    </nav>