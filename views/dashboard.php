<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'] . '/crime_report_system/config/db_connect.php';
//include $_SERVER['DOCUMENT_ROOT'] . '/crime_report_system/config/includes/navbar.php';
// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../views/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch complaints for the logged-in user
$sql = "SELECT id, description, location, phone_number, status, created_at 
        FROM complaints 
        WHERE user_id = '$user_id' 
        ORDER BY created_at DESC";
$result = $conn->query($sql);

if (!$result) {
    die("Database query failed: " . $conn->error);
}

// Fetch summary data
$sql_total = "SELECT COUNT(*) as total FROM complaints WHERE user_id = '$user_id'";
$result_total = $conn->query($sql_total);
$total_complaints = $result_total->fetch_assoc()['total'];

$sql_pending = "SELECT COUNT(*) as pending FROM complaints WHERE user_id = '$user_id' AND status = 'Pending'";
$result_pending = $conn->query($sql_pending);
$pending_complaints = $result_pending->fetch_assoc()['pending'];

$sql_resolved = "SELECT COUNT(*) as resolved FROM complaints WHERE user_id = '$user_id' AND status = 'Resolved'";
$result_resolved = $conn->query($sql_resolved);
$resolved_complaints = $result_resolved->fetch_assoc()['resolved'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel = "stylesheet" href = "/crime_report_system/assets/css/dash_board_style.css">
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
                        <a class="nav-link" href="dashboard.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="complaints.php">Complaints</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="profile.php">Profile</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 bg-dark text-white min-vh-100 p-4">
                <h3 class="text-center mb-4">Dashboard</h3>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link text-white" href="dashboard.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="view_complaints.php">Complaints</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="profile.php">Profile</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="logout.php">Logout</a>
                    </li>
                </ul>
            </div>

            <!-- Content -->
            <div class="col-md-9 p-4">
                <h1 class="text-center mb-4">Complaint Dashboard</h1>

                <!-- Summary Cards -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="card bg-primary text-white">
                            <div class="card-body">
                                <h5 class="card-title">Total Complaints</h5>
                                <p class="card-text"><?= $total_complaints; ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-warning text-white">
                            <div class="card-body">
                                <h5 class="card-title">Pending Complaints</h5>
                                <p class="card-text"><?= $pending_complaints; ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-success text-white">
                            <div class="card-body">
                                <h5 class="card-title">Resolved Complaints</h5>
                                <p class="card-text"><?= $resolved_complaints; ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Search Bar -->
                <input type="text" id="searchInput" class="form-control mb-3" placeholder="Search complaints...">

                <!-- Complaints Table -->
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Description</th>
                                <th>Location</th>
                                <th>Phone Number</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result->fetch_assoc()) { ?>
                            <tr>
                                <td><?= $row['id']; ?></td>
                                <td><?= $row['description']; ?></td>
                                <td><?= $row['location']; ?></td>
                                <td><?= $row['phone_number']; ?></td>
                                <td>
                                    <span class="badge 
                                        <?= $row['status'] === 'Pending' ? 'bg-warning' : 
                                           ($row['status'] === 'Resolved' ? 'bg-success' : 'bg-secondary'); ?>">
                                        <?= $row['status']; ?>
                                    </span>
                                </td>
                                <td><?= date('M d, Y h:i A', strtotime($row['created_at'])); ?></td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Floating Plus Button -->
    <a href="report_crime.php" class="btn btn-primary btn-lg rounded-circle shadow plus-btn plus-btn:hover" 
       style="position: fixed; bottom: 20px; right: 20px;">
        <i class="fas fa-plus"></i>
    </a>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS -->
    <script>
        document.getElementById('searchInput').addEventListener('input', function () {
            const filter = this.value.toLowerCase();
            const rows = document.querySelectorAll('tbody tr');

            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(filter) ? '' : 'none';
            });
        });
    </script>
</body>
</html>