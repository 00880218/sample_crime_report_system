<?php
$host = "localhost"; // Change if needed
$user = "root";      // Default for XAMPP
$pass = "";          // Default XAMPP password
$dbname = "crime_report_db"; // Your database name

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn) {
    echo "Database connected successfully!";
} else {
    die("Database connection failed: " . $conn->connect_error);
}
?>
