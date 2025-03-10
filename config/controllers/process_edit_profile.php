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
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $phone_number = trim($_POST['phone_number']);

    // File upload handling
    $target_dir = __DIR__ . '/../uploads/profile_pictures/';
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0755, true); // Create the directory if it doesn't exist
    }

    $profile_picture_path = null;
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
        $file_name = basename($_FILES['profile_picture']['name']);
        $file_tmp = $_FILES['profile_picture']['tmp_name'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($file_ext, $allowed_extensions)) {
            $new_file_name = uniqid('profile_', true) . '.' . $file_ext; // Generate a unique file name
            $profile_picture_path = $target_dir . $new_file_name;

            // Move the uploaded file
            if (move_uploaded_file($file_tmp, $profile_picture_path)) {
                $profile_picture_path = 'uploads/profile_pictures/' . $new_file_name; // Relative path for database
            } else {
                $_SESSION['error'] = "Failed to upload profile picture.";
                header("Location: /crime_report_system/views/edit_profile.php");
                exit();
            }
        } else {
            $_SESSION['error'] = "Invalid file type. Only JPG, JPEG, PNG, and GIF are allowed.";
            header("Location: /crime_report_system/views/edit_profile.php");
            exit();
        }
    }

    // Update user details in the database
    if ($profile_picture_path) {
        $sql = "UPDATE users SET username = ?, email = ?, phone_number = ?, profile_picture = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssi", $username, $email, $phone_number, $profile_picture_path, $user_id);
    } else {
        $sql = "UPDATE users SET username = ?, email = ?, phone_number = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $username, $email, $phone_number, $user_id);
    }

    if ($stmt->execute()) {
        $_SESSION['success'] = "Profile updated successfully!";
        header("Location: /crime_report_system/views/profile.php");
    } else {
        $_SESSION['error'] = "Error updating profile. Please try again.";
        header("Location: /crime_report_system/views/edit_profile.php");
    }

    $stmt->close();
    $conn->close();
} else {
    // Invalid request method
    $_SESSION['error'] = "Invalid request method!";
    header("Location: /crime_report_system/views/edit_profile.php");
    exit();
}
?>