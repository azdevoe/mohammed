<?php
session_start();
require 'db.php';

if (!isset($_SESSION["user"])) {
  header("Location: login.php");
  exit();
}

$user_id = $_SESSION["user"]["id"];
$file_id = $_GET["id"] ?? null;
$is_admin = isset($_GET["is_admin"]) && $_GET["is_admin"] == "1";

if ($file_id) {
  // First get the filename
  $stmt = $pdo->prepare("SELECT filename FROM files WHERE id = ? AND user_id = ?");
  $stmt->execute([$file_id, $user_id]);
  $file = $stmt->fetch();

  if ($file) {
    // Delete the physical file
    $filePath = "uploads/" . $file["filename"];
    if (file_exists($filePath)) {
      unlink($filePath);
    }

    // Delete the database record
    $delete = $pdo->prepare("DELETE FROM files WHERE id = ? AND user_id = ?");
    $delete->execute([$file_id, $user_id]);

    $_SESSION["flash"] = "File deleted successfully.";
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
