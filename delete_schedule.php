<?php
session_start();
require 'db.php';

if (!isset($_SESSION["user"])) {
  header("Location: login.php");
  exit();
}

$user_id = $_SESSION["user"]["id"];
$schedule_id = $_GET["id"] ?? null;
$is_admin = isset($_GET["is_admin"]) && $_GET["is_admin"] == "1";

if ($schedule_id) {
  $stmt = $pdo->prepare("DELETE FROM schedules WHERE id = ? AND user_id = ?");
  $stmt->execute([$schedule_id, $user_id]);
  
  if ($stmt->rowCount() > 0) {
    $_SESSION["flash"] = "Schedule deleted successfully.";
  } else {
    $_SESSION["flash"] = "Schedule not found.";
  }
}

// Redirect based on user type
if ($is_admin) {
  header("Location: admin_dashboard.php#schedule");
} else {
  header("Location: dashboard.php#schedule");
}
exit();
