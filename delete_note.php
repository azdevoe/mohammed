<?php
session_start();
require 'db.php';

if (!isset($_SESSION["user"])) {
  header("Location: login.php");
  exit();
}

$user_id = $_SESSION["user"]["id"];
$note_id = $_GET["id"] ?? null;

if ($note_id) {
  $stmt = $pdo->prepare("DELETE FROM notes WHERE id = ? AND user_id = ?");
  $stmt->execute([$note_id, $user_id]);
  $_SESSION["flash"] = "Note deleted successfully.";
}

// Redirect based on user role
$redirect_page = $_SESSION["user"]["is_admin"] ? "admin_dashboard.php" : "dashboard.php";
header("Location: " . $redirect_page . "#notes");
exit();
