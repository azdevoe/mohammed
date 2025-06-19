<?php
session_start();
require 'db.php';

if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION["user"]["id"];
$file_id = $_POST["file_id"] ?? null;
$is_admin = isset($_POST["is_admin"]) && $_POST["is_admin"] == "1";

if ($file_id && isset($_FILES["new_file"])) {
    // First get the old filename
    $stmt = $pdo->prepare("SELECT filename FROM files WHERE id = ? AND user_id = ?");
    $stmt->execute([$file_id, $user_id]);
    $file = $stmt->fetch();

    if ($file) {
        $old_filepath = "uploads/" . $file["filename"];
        
        // Handle the new file upload
        $new_file = $_FILES["new_file"];
        $filename = basename($new_file["name"]);
        $target_path = "uploads/" . $filename;

        // Delete old file if it exists
        if (file_exists($old_filepath)) {
            unlink($old_filepath);
        }

        // Upload new file
        if (move_uploaded_file($new_file["tmp_name"], $target_path)) {
            // Update database record
            $stmt = $pdo->prepare("UPDATE files SET filename = ? WHERE id = ? AND user_id = ?");
            $stmt->execute([$filename, $file_id, $user_id]);
            $_SESSION["flash"] = "File updated successfully.";
        } else {
            $_SESSION["flash"] = "Error uploading file.";
        }
    } else {
        $_SESSION["flash"] = "File not found.";
    }
}

// Redirect based on user type
if ($is_admin) {
    header("Location: admin_dashboard.php#files");
} else {
    header("Location: dashboard.php#files");
}
exit();
