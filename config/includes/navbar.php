<?php 
session_start();
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="index.php">
            <i class="fas fa-shield-alt"></i> Crime Report System
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="index.php">🏠 Home</a></li>
                <li class="nav-item"><a class="nav-link" href="report_crime.php">🚔 Report Crime</a></li>
                <li class="nav-item"><a class="nav-link" href="view_complaints.php">📜 View Complaints</a></li>
                <li class="nav-item"><a class="nav-link" href="contact.php">📞 Contact</a></li>
                
                <?php if (!isset($_SESSION['role'])) { ?>
                    <li class="nav-item"><a class="nav-link btn btn-warning text-dark" href="login.php">🔑 Login</a></li>
                <?php } else { ?>
                    <li class="nav-item"><a class="nav-link btn btn-danger text-white" href="logout.php">🚪 Logout</a></li>
                <?php } ?>
            </ul>
        </div>
    </div>
</nav>
