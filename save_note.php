<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user'])) {
  header("Location: login.php");
  exit();
}

$user_id = $_SESSION['user']['id'];
$content = $_POST['note'];

$stmt = $pdo->prepare("INSERT INTO notes (user_id, content) VALUES (?, ?)");
$stmt->execute([$user_id, $content]);

$_SESSION['flash'] = "Note saved successfully!";

// Redirect based on user role
$redirect_page = $_SESSION["user"]["is_admin"] ? "admin_dashboard.php" : "dashboard.php";
header("Location: " . $redirect_page . "#notes");
exit();
