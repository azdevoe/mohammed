<?php
session_start();
require 'db.php';

if (!isset($_SESSION["user"])) {
  header("Location: login.php");
  exit();
}

$user_id = $_SESSION["user"]["id"];

if (isset($_FILES['file']) && $_FILES['file']['error'] === 0) {
  $fileName = basename($_FILES['file']['name']);
  $uploadDir = 'uploads/';
  $targetPath = $uploadDir . $fileName;

  // Make sure upload directory exists
  if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0775, true);
  }

  // Move file
  if (move_uploaded_file($_FILES['file']['tmp_name'], $targetPath)) {
    $stmt = $pdo->prepare("INSERT INTO files (user_id, filename) VALUES (?, ?)");
    $stmt->execute([$user_id, $fileName]);
    $_SESSION["flash"] = "File uploaded successfully.";
  } else {
    $_SESSION["flash"] = "Failed to move uploaded file.";
  }
} else {
  $_SESSION["flash"] = "No file uploaded or upload error.";
}

// Redirect based on user role
$redirect_page = $_SESSION["user"]["is_admin"] ? "admin_dashboard.php" : "dashboard.php";
header("Location: " . $redirect_page . "#files");
exit();
