<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Here I Am</title>
  <!-- Bootstrap CSS CDN -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="/styles.css">
</head>
<body>
  <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
      <a class="navbar-brand" href="index.php">Here I Am</a>

      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <?php if (isset($_SESSION['user'])): ?>
            <!-- If logged in -->
            <?php if ($_SESSION['user']['is_admin']): ?>
              <!--- use nav-link instead of navbar-brand-->
              <li class="nav-item">
                <a class="navbar-brand" href="admin_dashboard.php">Admin Dashboard</a>
              </li>
              <li class="nav-item">
              <a class="navbar-brand" href="public_users.php">Users_info</a>
            </li>
            <?php else: ?>
              <li class="nav-item">
                <a class="navbar-brand" href="dashboard.php">Dashboard</a>
              </li>
            <?php endif; ?>
            <li class="nav-item">
              <a class="navbar-brand" href="logout.php">Logout</a>
            </li>
          <?php else: ?>
            <!-- If not logged in -->
            <li class="nav-item">
              <a class="navbar-brand" href="index.php">Home</a>
            </li>
            <li class="nav-item">
              <a class="navbar-brand" href="register.php">Register</a>
            </li>
            <li class="nav-item">
              <a class="navbar-brand" href="login.php">Login</a>
            </li>
            
          <?php endif; ?>
        </ul>
      </div>
    </div>
  </nav>

  <main class="container py-4">
    <?php if (isset($_SESSION['flash'])): ?>
      <div class="alert alert-success">
        <?= $_SESSION['flash'] ?>
        <?php unset($_SESSION['flash']); ?>
      </div>
    <?php endif; ?>





