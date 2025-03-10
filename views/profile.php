<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include $_SERVER['DOCUMENT_ROOT'] . '/crime_report_system/config/db_connect.php'; // Include database connection
$user_id = $_SESSION['user_id'];

// Fetch user details
$sql = "SELECT username, email, phone_number, profile_picture FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

// Fetch user's complaints
$sql_complaints = "SELECT id, description, status, created_at FROM complaints WHERE user_id = ? ORDER BY created_at DESC";
$stmt_complaints = $conn->prepare($sql_complaints);
$stmt_complaints->bind_param("i", $user_id);
$stmt_complaints->execute();
$result_complaints = $stmt_complaints->get_result();
$complaints = $result_complaints->fetch_all(MYSQLI_ASSOC);
$stmt_complaints->close();
$conn->close();
//include $_SERVER['DOCUMENT_ROOT'] . '/crime_report_system/config/includes/navbar.php'

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Profile</h1>
        <div class="card mb-4">
            <div class="card-body">
                <?php if ($user['profile_picture']) { ?>
                    <img src="/crime_report_system/<?= $user['profile_picture']; ?>" alt="Profile Picture" class="img-fluid rounded-circle mb-3" style="width: 150px; height: 150px;">
                <?php } else { ?>
                    <img src="https://via.placeholder.com/150" alt="Profile Picture" class="img-fluid rounded-circle mb-3">
                <?php } ?>
                <h5 class="card-title"><?= $user['username']; ?></h5>
                <p class="card-text">Email: <?= $user['email']; ?></p>
                <p class="card-text">Phone Number: <?= $user['phone_number']; ?></p>
                <a href="edit_profile.php" class="btn btn-primary">Edit Profile</a>
            </div>
        </div>

        <!-- Complaints Section -->
        <h2>Your Complaints</h2>
        <?php if (count($complaints) > 0) { ?>
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Description</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($complaints as $complaint) { ?>
                            <tr>
                                <td><?= $complaint['id']; ?></td>
                                <td><?= $complaint['description']; ?></td>
                                <td>
                                    <span class="badge 
                                        <?= $complaint['status'] === 'Pending' ? 'bg-warning' : 
                                           ($complaint['status'] === 'Resolved' ? 'bg-success' : 'bg-secondary'); ?>">
                                        <?= $complaint['status']; ?>
                                    </span>
                                </td>
                                <td><?= date('M d, Y h:i A', strtotime($complaint['created_at'])); ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        <?php } else { ?>
            <p>No complaints found.</p>
        <?php } ?>
    </div>
</body>
</html>