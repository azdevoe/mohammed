<?php
session_start();
require 'header.php';
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $email = $_POST["email"];
  $password = $_POST["password"];

  $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
  $stmt->execute([$email]);
  $user = $stmt->fetch();

  if ($user && password_verify($password, $user["password"])) {
    if (!$user["is_approved"] && !$user["is_admin"]) {
      $error = "Your account is pending approval. Please wait for admin approval.";
    } else {
      $_SESSION["user"] = $user;
      $_SESSION["flash"] = "Welcome back!";
      header("Location: " . ($user["is_admin"] ? "admin_dashboard.php" : "dashboard.php"));
      exit();
    }
  } else {
    $error = "Invalid email or password.";
  }
}
?>

<div class="row justify-content-center">
  <div class="col-md-6">
    <div class="card shadow-sm p-4">
      <h2 class="mb-4">Login</h2>
      <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?= $error ?></div>
      <?php endif; ?>
      <form method="POST" action="login.php">
        <div class="mb-3">
          <input type="email" name="email" class="form-control" placeholder="Email" required>
        </div>
        <div class="mb-3">
          <input type="password" name="password" class="form-control" placeholder="Password" required>
        </div>
        <button type="submit" class="btn btn-success w-100">Login</button>
      </form>
    </div>
  </div>
</div>

<?php require 'footer.php'; ?>

