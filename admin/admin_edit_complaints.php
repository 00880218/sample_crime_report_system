<?php
session_start();

// Check if the user is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

include "config/db_connect.php";

// Fetch complaint details
$complaint_id = $_GET['id'];
$sql_complaint = "SELECT c.*, u.username as user_name 
                  FROM complaints c 
                  JOIN users u ON c.user_id = u.id 
                  WHERE c.id = '$complaint_id'";
$result_complaint = $conn->query($sql_complaint);
$complaint = $result_complaint->fetch_assoc();

// Fetch all officers
$sql_officers = "SELECT id, username FROM users WHERE role = 'officer'";
$result_officers = $conn->query($sql_officers);

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $status = $_POST['status'];
    $assigned_officer = $_POST['assigned_officer'];
    $resolution_notes = $_POST['resolution_notes'];

    // Update complaint
    $sql_update = "UPDATE complaints 
                   SET status = '$status', 
                       assigned_officer = '$assigned_officer', 
                       resolution_notes = '$resolution_notes' 
                   WHERE id = '$complaint_id'";
    if ($conn->query($sql_update)) {
        $_SESSION['success'] = "Complaint updated successfully!";
        header("Location: admin_complaints.php");
    } else {
        $_SESSION['error'] = "Error updating complaint: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Complaint</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <!-- Navbar (same as admin_dashboard.php) -->

    <!-- Main Content -->
    <div class="container mt-5">
        <h1 class="text-center mb-4">Edit Complaint</h1>

        <!-- Complaint Form -->
        <form method="POST">
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" rows="5" readonly><?= $complaint['description']; ?></textarea>
            </div>
            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select class="form-control" id="status" name="status" required>
                    <option value="Pending" <?= $complaint['status'] === 'Pending' ? 'selected' : ''; ?>>Pending</option>
                    <option value="In Progress" <?= $complaint['status'] === 'In Progress' ? 'selected' : ''; ?>>In Progress</option>
                    <option value="Resolved" <?= $complaint['status'] === 'Resolved' ? 'selected' : ''; ?>>Resolved</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="assigned_officer" class="form-label">Assigned Officer</label>
                <select class="form-control" id="assigned_officer" name="assigned_officer">
                    <option value="">None</option>
                    <?php while ($officer = $result_officers->fetch_assoc()) { ?>
                    <option value="<?= $officer['id']; ?>" <?= $complaint['assigned_officer'] === $officer['id'] ? 'selected' : ''; ?>>
                        <?= $officer['username']; ?>
                    </option>
                    <?php } ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="resolution_notes" class="form-label">Resolution Notes</label>
                <textarea class="form-control" id="resolution_notes" name="resolution_notes" rows="5"><?= $complaint['resolution_notes']; ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Update Complaint</button>
        </form>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>