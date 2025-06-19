<?php
session_start();
require 'db.php';

if (!isset($_SESSION["user"])) {
  header("Location: login.php");
  exit();
}

$user_id = $_SESSION["user"]["id"];
$date = $_POST["date"];
$time = $_POST["time"];
$event = $_POST["event"];

$stmt = $pdo->prepare("INSERT INTO schedules (user_id, event_date, event_time, description) VALUES (?, ?, ?, ?)");
$stmt->execute([$user_id, $date, $time, $event]);
$_SESSION['flash'] = "Schedule saved successfully!";

// Redirect based on user role
$redirect_page = $_SESSION["user"]["is_admin"] ? "admin_dashboard.php" : "dashboard.php";
header("Location: " . $redirect_page . "#schedule");
exit();
