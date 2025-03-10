<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'] . '/crime_report_system/config/db_connect.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (empty($username) || empty($password)) {
        $_SESSION['error'] = "Username and password are required!";
        header("Location: /crime_report_system/views/login.php");
        exit();
    }

    $stmt = $conn->prepare("SELECT id, username, password, role FROM users WHERE username = ?");
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("s", $username);
    if (!$stmt->execute()) {
        die("Execute failed: " . $stmt->error);
    }

    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($id, $username, $hashed_password, $role);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            $_SESSION['user_id'] = $id;
            $_SESSION['username'] = $username;
            $_SESSION['role'] = $role;
        
            if ($role === 'admin') {
                header("Location: /crime_report_system/admin/admin_dashboard.php");
            } else {
                header("Location: /crime_report_system/views/dashboard.php"); // Updated path
            }
            exit();
        } else {
            $_SESSION['error'] = "Invalid username or password!";
            header("Location: /crime_report_system/views/login.php");;
            exit();
        }
    } else {
        $_SESSION['error'] = "Invalid username or password!";
        header("Location: /crime_report_system/views/login.php");
        exit();
    }

    $stmt->close();
    $conn->close();
} else {
    $_SESSION['error'] = "Invalid request method!";
    header("Location: /crime_report_system/views/login.php");
    exit();
}
?>