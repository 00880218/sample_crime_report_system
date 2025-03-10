<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../views/login.php");
    exit();
}

include $_SERVER['DOCUMENT_ROOT'] . '/crime_report_system/config/db_connect.php';// Include database connection

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Get form data
    $user_id = $_SESSION['user_id'];
    $description = trim($_POST['description']);
    $location = trim($_POST['location']);
    $phone_number = trim($_POST['phone_number']);
    $latitude = trim($_POST['latitude']);
    $longitude = trim($_POST['longitude']);

    // File upload handling
    $target_dir = "../uploads/";
    $evidence_path = $target_dir . basename($_FILES["evidence"]["name"]);
    $signature_path = $target_dir . basename($_FILES["signature"]["name"]);

    // Move uploaded files
    move_uploaded_file($_FILES["evidence"]["tmp_name"], $evidence_path);
    move_uploaded_file($_FILES["signature"]["tmp_name"], $signature_path);

    // Insert into database
    $sql = "INSERT INTO complaints (user_id, description, location, phone_number, evidence, signature, latitude, longitude, status, created_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'Pending', NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isssssdd", $user_id, $description, $location, $phone_number, $evidence_path, $signature_path, $latitude, $longitude);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Complaint submitted successfully!";
        header("Location: ../views/dashboard.php");
    } else {
        $_SESSION['error'] = "Error submitting complaint. Please try again.";
        header("Location: ../views/report_crime.php");
    }

    $stmt->close();
    $conn->close();
} else {
    // Invalid request method
    $_SESSION['error'] = "Invalid request method!";
    header("Location: ../views/report_crime.php");
    exit();
}
?>