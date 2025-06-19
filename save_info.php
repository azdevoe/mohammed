<?php
session_start();
require 'db.php';

if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION["user"]["id"];
$is_admin = $_SESSION["user"]["is_admin"] ?? false;

// Check if profile exists
$stmt = $pdo->prepare("SELECT * FROM profiles WHERE user_id = ?");
$stmt->execute([$user_id]);
$profile = $stmt->fetch();

$fields = [
    'age', 'sex', 'profession', 'education', 'hobby', 'about',
    'dob', 'email', 'phone', 'address', 'likes', 'dislikes'
];

$data = [];
foreach ($fields as $field) {
    $data[$field] = $_POST[$field] ?? '';
}

if ($profile) {
    // Update existing profile
    $sql = "UPDATE profiles SET " . implode(" = ?, ", array_keys($data)) . " = ? WHERE user_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([...array_values($data), $user_id]);
} else {
    // Create new profile
    $sql = "INSERT INTO profiles (user_id, " . implode(", ", array_keys($data)) . ") VALUES (?, " . str_repeat("?, ", count($data) - 1) . "?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$user_id, ...array_values($data)]);
}

$_SESSION["flash"] = "Profile information saved successfully!";

// Redirect based on user type
if ($is_admin) {
    header("Location: admin_dashboard.php#profile");
} else {
    header("Location: dashboard.php#profile");
}
exit();
