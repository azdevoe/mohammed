<?php
require 'header.php';
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $name = $_POST["name"];
  $email = $_POST["email"];
  $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

  $stmt = $pdo->prepare("INSERT INTO users (name, email, password, is_approved) VALUES (?, ?, ?, FALSE)");
  try {
    $stmt->execute([$name, $email, $password]);
    $_SESSION['flash'] = "Registration successful! Please wait for admin approval before logging in.";
    header("Location: login.php");
    exit();
  } catch (PDOException $e) {
    $error = "Email already exists!";
  }
}
?>

<div class="row justify-content-center">
  <div class="col-md-6">
    <div class="card shadow-sm p-4">
      <h2 class="mb-4">Register</h2>
      
      <div class="alert alert-info mb-4">
        <strong>Note:</strong> After registration, your account will need to be approved by an administrator before you can log in.
      </div>
      
      <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?= $error ?></div>
      <?php endif; ?>
      <form method="POST" action="register.php">
        <div class="mb-3">
          <input type="text" name="name" class="form-control" placeholder="Full Name" required>
        </div>
        <div class="mb-3">
          <input type="email" name="email" class="form-control" placeholder="Email" required>
        </div>
        <div class="mb-3">
          <input type="password" name="password" class="form-control" placeholder="Password" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Register</button>
      </form>
    </div>
  </div>
</div>

<?php require 'footer.php'; ?>


