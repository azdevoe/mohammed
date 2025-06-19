<?php
require 'db.php';

$search    = $_GET['search'] ?? '';
$gender    = $_GET['gender'] ?? '';
$education = $_GET['education'] ?? '';
$page      = max(1, (int)($_GET['page'] ?? 1));
$perPage   = 4;
$offset    = ($page - 1) * $perPage;

$where = [];
$params = [];

if ($search) {
  $where[] = 'users.name LIKE ?';
  $params[] = "%$search%";
}
if ($gender) {
  $where[] = 'profiles.sex = ?';
  $params[] = $gender;
}
if ($education) {
  $where[] = 'profiles.education = ?';
  $params[] = $education;
}

$sql = "
  SELECT users.id, users.name, profiles.sex, profiles.profession, profiles.education, profiles.email
  FROM users
  LEFT JOIN profiles ON users.id = profiles.user_id
";
if (!empty($where)) {
  $sql .= " WHERE " . implode(" AND ", $where);
}
$sql .= " ORDER BY users.name LIMIT $perPage OFFSET $offset";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Pagination count
$countSql = "
  SELECT COUNT(*) FROM users
  LEFT JOIN profiles ON users.id = profiles.user_id
";
if (!empty($where)) {
  $countSql .= " WHERE " . implode(" AND ", $where);
}
$countStmt = $pdo->prepare($countSql);
$countStmt->execute($params);
$totalUsers = $countStmt->fetchColumn();
$totalPages = ceil($totalUsers / $perPage);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Public User Directory</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    
  body {
    background: linear-gradient(to right, #e3f2fd, #fce4ec);
  }

  .card-custom {
    background: #ffffff;
    border-left: 6px solid #0d6efd;
    border-radius: 10px;
    padding: 15px;
    transition: transform 0.2s ease-in-out;
  }

  .card-custom:hover {
    transform: scale(1.02);
  }

  .form-select, .form-control {
    border-radius: 30px;
  }

  .btn {
    border-radius: 30px;
  }

  @media print {
    body {
      background: #fff !important;
    }

    .btn, .form-control, .form-select, form, nav, .pagination, .back-print-controls {
      display: none !important;
    }

    .card {
      page-break-inside: avoid;
      break-inside: avoid;
      border: 1px solid #000 !important;
      margin-bottom: 20px;
    }

    .row, .col, .container {
      display: block !important;
      width: 100% !important;
    }

    .card-body {
      padding: 15px !important;
    }

    .row-cols-1, .row-cols-md-2 {
      display: block !important;
    }
  }
</style>

 
</head>
<body>
  <div class="container py-5">
    <h2 class="text-center mb-4 text-primary fw-bold">📘 Public User Directory</h2>  

    <!-- 🔘 Top Buttons -->
    <div class="d-flex justify-content-between align-items-center mb-4">
      <a href="#" onclick="window.print()" class="btn btn-outline-primary">🖨️ Print User List</a>
      <a href="admin_dashboard.php" class="btn btn-outline-dark">🏠 Back to Admin Dashboard</a>
    </div>

    <!-- 🔍 Filter Form -->
    <form method="GET" class="row g-3 mb-4">
      <div class="col-md-4">
        <input type="text" name="search" class="form-control shadow-sm" placeholder="🔍 Search by name..." value="<?= htmlspecialchars($search) ?>">
      </div>
      <div class="col-md-3">
        <select name="gender" class="form-select shadow-sm">
          <option value="">All Genders</option>
          <option value="Male" <?= $gender === 'Male' ? 'selected' : '' ?>>Male</option>
          <option value="Female" <?= $gender === 'Female' ? 'selected' : '' ?>>Female</option>
          <option value="Other" <?= $gender === 'Other' ? 'selected' : '' ?>>Other</option>
        </select>
      </div>
      <div class="col-md-3">
        <select name="education" class="form-select shadow-sm">
          <option value="">All Education Levels</option>
          <option value="Undergraduate" <?= $education === 'Undergraduate' ? 'selected' : '' ?>>Undergraduate</option>
          <option value="Graduate" <?= $education === 'Graduate' ? 'selected' : '' ?>>Graduate</option>
        </select>
      </div>
      <div class="col-md-2">
        <button type="submit" class="btn btn-success w-100 shadow">Filter</button>
      </div>
    </form>

    <!-- 📋 User Cards -->
    <?php if (count($users) > 0): ?>
      <div class="row row-cols-1 row-cols-md-2 g-4">
        <?php foreach ($users as $user): ?>
          <div class="col">
            <div class="card card-custom shadow-sm h-100">
              <div class="card-body">
                <h5 class="card-title text-primary"><?= htmlspecialchars($user['name']) ?></h5>
                <p class="mb-1"><strong>Gender:</strong> <?= htmlspecialchars($user['sex']) ?: 'N/A' ?></p>
                <p class="mb-1"><strong>Profession:</strong> <?= htmlspecialchars($user['profession']) ?: 'N/A' ?></p>
                <p class="mb-1"><strong>Education:</strong> <?= htmlspecialchars($user['education']) ?: 'N/A' ?></p>
                <p class="mb-3"><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?: 'N/A' ?></p>
                <a href="view_user.php?id=<?= $user['id'] ?>" class="btn btn-outline-info btn-sm">View Full Profile</a>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>

      <!-- 📄 Pagination -->
      <?php if ($totalPages > 1): ?>
        <nav class="mt-4">
          <ul class="pagination justify-content-center">
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
              <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                <a class="page-link" href="?<?= http_build_query(array_merge($_GET, ['page' => $i])) ?>">
                  <?= $i ?>
                </a>
              </li>
            <?php endfor; ?>
          </ul>
        </nav>
      <?php endif; ?>

    <?php else: ?>
      <div class="alert alert-info text-center">No users found.</div>
    <?php endif; ?>
  </div>
</body>
</html>
