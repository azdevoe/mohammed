<?php
require 'db.php';

$id = $_GET['id'] ?? null;

if (!$id) {
  echo "User ID missing.";
  exit;
}

$stmt = $pdo->prepare("
  SELECT users.name, profiles.* 
  FROM users 
  LEFT JOIN profiles ON users.id = profiles.user_id 
  WHERE users.id = ?
");
$stmt->execute([$id]);
$user = $stmt->fetch();

if (!$user) {
  echo "User not found.";
  exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title><?= htmlspecialchars($user['name']) ?> - Profile</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(to right, #e3f2fd, #fce4ec);
    }
    .card-custom {
      background-color: #ffffff;
      border-left: 6px solid #0d6efd;
      padding: 20px;
      border-radius: 12px;
    }
    .card-custom h2 {
      color: #0d6efd;
    }
    .section-header {
      font-weight: bold;
      color: #495057;
      background: #e9ecef;
      padding: 8px 12px;
      border-radius: 6px;
      margin-top: 20px;
    }
    @media print {
      .no-print {
        display: none !important;
      }
      .card-custom {
        border: 1px solid #000;
        padding: 20px;
      }
    }
  </style>
</head>
<body>

<div class="container py-5">
  <!-- 🔘 Navigation -->
  <div class="d-flex justify-content-between align-items-center mb-4 no-print">
    <a href="public_users.php" class="btn btn-dark">← Back to Users</a>
    <button onclick="window.print()" class="btn btn-primary">🖨️ Print Profile</button>
  </div>

  <!-- 🧾 Profile -->
  <div class="card card-custom shadow">
    <h2 class="mb-4"><?= htmlspecialchars($user['name']) ?>'s Profile</h2>

    <div class="section-header">Basic Information</div>
    <div class="row mt-2">
      <div class="col-md-6"><strong>Date of Birth:</strong> <?= htmlspecialchars($user['dob']) ?: 'N/A' ?></div>
      <div class="col-md-6"><strong>Gender:</strong> <?= htmlspecialchars($user['sex']) ?: 'N/A' ?></div>
      <div class="col-md-6"><strong>Profession:</strong> <?= htmlspecialchars($user['profession']) ?: 'N/A' ?></div>
      <div class="col-md-6"><strong>Education:</strong> <?= htmlspecialchars($user['education']) ?: 'N/A' ?></div>
    </div>

    <div class="section-header">Personal Interests</div>
    <div class="row mt-2">
      <div class="col-md-6"><strong>Hobby:</strong> <?= htmlspecialchars($user['hobby']) ?: 'N/A' ?></div>
      <div class="col-md-6"><strong>Likes:</strong> <?= htmlspecialchars($user['likes']) ?: 'N/A' ?></div>
      <div class="col-md-6"><strong>Dislikes:</strong> <?= htmlspecialchars($user['dislikes']) ?: 'N/A' ?></div>
    </div>

    <div class="section-header">Contact</div>
    <div class="row mt-2">
      <div class="col-md-6"><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?: 'N/A' ?></div>
      <div class="col-md-6"><strong>Phone:</strong> <?= htmlspecialchars($user['phone']) ?: 'N/A' ?></div>
    </div>

    <div class="section-header">Address</div>
    <p class="mt-2"><?= htmlspecialchars($user['address']) ?: 'N/A' ?></p>

    <div class="section-header">About Work</div>
    <p class="mt-2"><?= nl2br(htmlspecialchars($user['about'])) ?: 'N/A' ?></p>
  </div>
</div>

</body>
</html>
