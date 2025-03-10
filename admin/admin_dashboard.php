<?php
session_start();

// Check if the user is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

include "config/db_connect.php";

// Fetch total complaints
$sql_total_complaints = "SELECT COUNT(*) as total FROM complaints";
$result_total_complaints = $conn->query($sql_total_complaints);
$total_complaints = $result_total_complaints->fetch_assoc()['total'];

// Fetch pending complaints
$sql_pending_complaints = "SELECT COUNT(*) as pending FROM complaints WHERE status = 'Pending'";
$result_pending_complaints = $conn->query($sql_pending_complaints);
$pending_complaints = $result_pending_complaints->fetch_assoc()['pending'];

// Fetch resolved complaints
$sql_resolved_complaints = "SELECT COUNT(*) as resolved FROM complaints WHERE status = 'Resolved'";
$result_resolved_complaints = $conn->query($sql_resolved_complaints);
$resolved_complaints = $result_resolved_complaints->fetch_assoc()['resolved'];

// Fetch total users
$sql_total_users = "SELECT COUNT(*) as total FROM users";
$result_total_users = $conn->query($sql_total_users);
$total_users = $result_total_users->fetch_assoc()['total'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Crime Report System</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="admin_dashboard.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="admin_complaints.php">Complaints</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="admin_users.php">Users</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container mt-5">
        <h1 class="text-center mb-4">Admin Dashboard</h1>

        <!-- Summary Cards -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <h5 class="card-title">Total Complaints</h5>
                        <p class="card-text"><?= $total_complaints; ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <h5 class="card-title">Pending Complaints</h5>
                        <p class="card-text"><?= $pending_complaints; ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <h5 class="card-title">Resolved Complaints</h5>
                        <p class="card-text"><?= $resolved_complaints; ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <h5 class="card-title">Total Users</h5>
                        <p class="card-text"><?= $total_users; ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>