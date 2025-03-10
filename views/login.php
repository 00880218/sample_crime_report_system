<?php 
session_start();


// Redirect already logged-in users securely
if (isset($_SESSION['role'])) {
    if ($_SESSION['role'] === "admin") {
        header("Location: admin_dashboard.php");
        exit();
    } else {
        header("Location: dashboard.php");
        exit();
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crime Report System - Login</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome (For Icons) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="/crime_report_system/assets/css/style.css">
<script src="/crime_report_system/assets/js/script.js"></script>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <!-- Tamil Nadu Police Logo -->
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-shield-alt"></i> Crime Report System
            </a>

            <!-- Mobile Menu Button -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Navbar Links -->
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="index.php"><i class="fas fa-home"></i> Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="report_crime.php"><i class="fas fa-exclamation-triangle"></i> Report Crime</a></li>
                    <li class="nav-item"><a class="nav-link" href="view_complaints.php"><i class="fas fa-eye"></i> View Complaints</a></li>
                    <li class="nav-item"><a class="nav-link" href="contact.php"><i class="fas fa-phone"></i> Contact</a></li>
                    
                    <!-- Login/Logout Button -->
                    <?php if (!isset($_SESSION['role'])) { ?>
                        <li class="nav-item"><a class="nav-link btn btn-warning text-dark" href="login.php"><i class="fas fa-sign-in-alt"></i> Login</a></li>
                    <?php } else { ?>
                        <li class="nav-item"><a class="nav-link btn btn-danger text-white" href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                    <?php } ?>
                </ul>
            </div>
        </div>
    </nav>

    <marquee behavior="scroll" direction="left" scrollamount="5" class="marquee-text">
        ⚠️ Report crimes immediately | 📞 Emergency: 100 | 🚔 Tamil Nadu Police Helpline: 112 | 💡 Safety Tip: Never share personal details online!
    </marquee>

    <!-- Emergency Contact Banner -->
    <div class="alert alert-danger text-center fw-bold">
        🚨 <i class="fas fa-phone"></i> Emergency? Call 100 or your local police station immediately!
    </div>

    <!-- Login Container -->
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-lg p-4">
                    <h2 class="text-center text-primary"><i class="fas fa-user-lock"></i> Login</h2>

                    <!-- Login Form -->
                    <form action="/crime_report_system/config/controllers/process_login.php" method="POST">
                        <!-- Dropdown to choose User or Admin -->
                        <div class="mb-3">
                            <label for="login-type" class="form-label">Login as:</label>
                            <select id="login-type" name="login_type" class="form-select" required>
                                <option value="" disabled selected>Select Role</option>
                                <option value="user">User</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>

                        <!-- Username Field -->
                        <div class="mb-3">
                            <label for="username" class="form-label">Username:</label>
                            <input type="text" id="username" name="username" class="form-control" placeholder="Enter Username" required autocomplete="username">
                        </div>

                        <!-- Password Field -->
                        <div class="mb-3">
                            <label for="password" class="form-label">Password:</label>
                            <input type="password" id="password" name="password" class="form-control" placeholder="Enter Password" required autocomplete="off">
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="btn btn-primary w-100">Login</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Register Link -->
        <div class="text-center mt-3">
            <a href="register.php" class="text-warning"><i class="fas fa-user-plus"></i> New user? Register here</a>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <? 
    include $_SERVER['DOCUMENT_ROOT'] . '/crime_report_system/config/footer.php';
    ?>
</body>
</html>

