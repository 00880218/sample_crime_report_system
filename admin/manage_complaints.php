<?php
session_start();

// Check if the user is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

include "config/db_connect.php";

// Fetch all complaints
$sql_complaints = "SELECT c.id, c.description, c.status, c.created_at, u.username as user_name 
                   FROM complaints c 
                   JOIN users u ON c.user_id = u.id 
                   ORDER BY c.created_at DESC";
$result_complaints = $conn->query($sql_complaints);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Complaints</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <!-- Navbar (same as admin_dashboard.php) -->

    <!-- Main Content -->
    <div class="container mt-5">
        <h1 class="text-center mb-4">Manage Complaints</h1>

        <!-- Complaints Table -->
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Description</th>
                        <th>User</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result_complaints->fetch_assoc()) { ?>
                    <tr>
                        <td><?= $row['id']; ?></td>
                        <td><?= $row['description']; ?></td>
                        <td><?= $row['user_name']; ?></td>
                        <td>
                            <span class="badge 
                                <?= $row['status'] === 'Pending' ? 'bg-warning' : 
                                   ($row['status'] === 'Resolved' ? 'bg-success' : 'bg-secondary'); ?>">
                                <?= $row['status']; ?>
                            </span>
                        </td>
                        <td><?= date('M d, Y h:i A', strtotime($row['created_at'])); ?></td>
                        <td>
                            <a href="admin_edit_complaint.php?id=<?= $row['id']; ?>" class="btn btn-sm btn-primary">Edit</a>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 
