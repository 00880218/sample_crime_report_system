<?php
session_start();
include '../config/db_connect.php'; // Database connection

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);
    $role = $_POST['role'] ?? '';

    // Validate required fields
    if (empty($username) || empty($password) || empty($confirm_password) || empty($role)) {
        $_SESSION['error'] = "All fields are required!";
        header("Location: ../register.php");
        exit();
    }

    // Check if passwords match
    if ($password !== $confirm_password) {
        $_SESSION['error'] = "Passwords do not match!";
        header("Location: ../register.php");
        exit();
    }

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Check if username already exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $_SESSION['error'] = "Username already exists!";
        header("Location: ../register.php");
        exit();
    }
    $stmt->close();

    // Insert new user
    $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $hashed_password, $role);
    
    if ($stmt->execute()) {
        // Get the ID of the newly registered user
        $new_user_id = $stmt->insert_id;

        // If the user is an admin, log the action in adminlogs
        if ($role === 'admin') {
            $action = "New admin registered: $username";
            $log_stmt = $conn->prepare("INSERT INTO adminlogs (admin_id, action) VALUES (?, ?)");
            $log_stmt->bind_param("is", $new_user_id, $action);
            $log_stmt->execute();
            $log_stmt->close();
        }

        $_SESSION['success'] = "Registration successful! Please login.";
        header("Location: ../login.php");
    } else {
        $_SESSION['error'] = "Something went wrong. Try again!";
        header("Location: ../register.php");
    }

    $stmt->close();
    $conn->close();
}
?>