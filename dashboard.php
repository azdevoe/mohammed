<?php
session_start();
require 'db.php';

if (!isset($_SESSION["user"])) {
  header("Location: login.php");
  exit();
}

$user = $_SESSION["user"];
$user_id = $user["id"];
date_default_timezone_set('Africa/Lagos');

// Fetch profile info
$stmt = $pdo->prepare("SELECT * FROM profiles WHERE user_id = ?");
$stmt->execute([$user_id]);
$profile = $stmt->fetch();

// Fetch stats
$noteCount = $pdo->query("SELECT COUNT(*) FROM notes WHERE user_id = $user_id")->fetchColumn();
$scheduleCount = $pdo->query("SELECT COUNT(*) FROM schedules WHERE user_id = $user_id")->fetchColumn();
$fileCount = $pdo->query("SELECT COUNT(*) FROM files WHERE user_id = $user_id")->fetchColumn();

// Fetch schedules
$scheduleStmt = $pdo->prepare("SELECT * FROM schedules WHERE user_id = ? ORDER BY event_date, event_time");
$scheduleStmt->execute([$user_id]);
$schedules = $scheduleStmt->fetchAll();
// Fetch notes
$noteStmt = $pdo->prepare("SELECT * FROM notes WHERE user_id = ? ORDER BY created_at DESC");
$noteStmt->execute([$user_id]);
$notes = $noteStmt->fetchAll();
// Fetch files
$fileStmt = $pdo->prepare("SELECT * FROM files WHERE user_id = ?");
$fileStmt->execute([$user_id]);
$files = $fileStmt->fetchAll();
//search schedules by date
$searchResults = [];
if (!empty($_GET['search_date'])) {
  $searchDate = $_GET['search_date'];
  $searchStmt = $pdo->prepare("SELECT * FROM schedules WHERE user_id = ? AND event_date = ?");
  $searchStmt->execute([$user_id, $searchDate]);
  $searchResults = $searchStmt->fetchAll();
}
require 'header.php';
?>
<!-- Responsive Header -->
<div class="d-flex justify-content-between align-items-center p-2 border-bottom">
  <button class="btn btn-outline-primary d-md-none" id="toggle-sidebar">☰ Menu</button>
  
</div>

<div class="container-fluid">
  <div class="row">
    <!-- Sidebar -->
    <div class="col-md-3 bg-light vh-100 p-4 d-none d-md-block" id="sidebar">
      <h4>Menu</h4>
      <div class="list-group">
        <a href="#profile" class="list-group-item list-group-item-action">Profile</a>
        <a href="#schedule" class="list-group-item list-group-item-action">Schedules</a>
        <a href="#notes" class="list-group-item list-group-item-action">Notes</a>
        <a href="#files" class="list-group-item list-group-item-action">Files</a>
        <a href="#websearch" class="list-group-item list-group-item-action">🌐 Web Search</a>
        <a href="logout.php" class="list-group-item list-group-item-action text-danger">Logout</a>
        <!-- Add this inside your sidebar menu -->
        

      </div>
    </div>

    <!-- Main Content -->
    <div class="col-md-9 p-4">
      <h2>Hello, <?= htmlspecialchars($user["name"]) ?> 👋</h2>

      <!-- Stats -->
      <div class="row mb-4">
        <div class="col-md-4">
          <div class="card text-white bg-primary">
            <div class="card-body">
              <h5 class="card-title">Notes</h5>
              <p class="card-text fs-4"><?= $noteCount ?></p>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card text-white bg-success">
            <div class="card-body">
              <h5 class="card-title">Schedules</h5>
              <p class="card-text fs-4"><?= $scheduleCount ?></p>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card text-white bg-dark">
            <div class="card-body">
              <h5 class="card-title">Files</h5>
              <p class="card-text fs-4"><?= $fileCount ?></p>
            </div>
          </div>
        </div>
      </div>
      <!-- Today's Reminders -->
<?php
date_default_timezone_set('Africa/Lagos'); // set your timezone
$now = date("Y-m-d H:i:s");
$today = date("Y-m-d");

// Get events within the next 1 hour
$reminderQuery = $pdo->prepare("
  SELECT * FROM schedules 
  WHERE user_id = ? 
    AND event_date = CURDATE() 
    AND CONCAT(event_date, ' ', event_time) >= NOW()
    AND CONCAT(event_date, ' ', event_time) <= DATE_ADD(NOW(), INTERVAL 1 HOUR)
  ORDER BY event_time ASC
");
$reminderQuery->execute([$user_id]);
$reminders = $reminderQuery->fetchAll();


// New weekly upcoming events query
$weeklyUpcomingQuery = $pdo->prepare("
  SELECT * FROM schedules 
  WHERE user_id = ? 
    AND event_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 7 DAY)
  ORDER BY event_date, event_time
");
$weeklyUpcomingQuery->execute([$user_id]);
$weeklyUpcomingEvents = $weeklyUpcomingQuery->fetchAll();
?>

<?php if (count($reminders) > 0): ?>
  <div class="alert alert-warning">
    <h5 class="mb-2">⏰ Upcoming Reminder(s):</h5>
    <ul class="mb-0">
      <?php foreach ($reminders as $r): ?>
        <li><strong><?= $r['event_time'] ?></strong> — <?= htmlspecialchars($r['description']) ?></li>
      <?php endforeach; ?>
    </ul>
  </div>
<?php else: ?>
  <div class="alert alert-info">
    <strong>No upcoming reminders in the next hour.</strong>
  </div>
<?php endif; ?>
<!-- New Weekly Upcoming Events Summary -->
<?php if (count($weeklyUpcomingEvents) > 0): ?>
  <div class="card alert-info mb-4 p-3">
    <h5 class="mb-3">📅 Upcoming Events This Week</h5>
    <ul class="list-group">
      <?php foreach ($weeklyUpcomingEvents as $event): ?>
        <li class="list-group-item">
          <strong><?= htmlspecialchars($event['event_date']) ?></strong> at <strong><?= htmlspecialchars($event['event_time']) ?></strong> — <?= htmlspecialchars($event['description']) ?>
        </li>
      <?php endforeach; ?>
    </ul>
  </div>
<?php else: ?>
  <div class="alert alert-light mb-4 text-center">
    No upcoming events in the next 7 days.
  </div>
<?php endif; ?>


<section id="profile" class="mb-5">
  <h3>General Information</h3>
  <form method="POST" action="save_info.php" class="card p-4 shadow-sm mt-3">

    <div class="row mb-3">
      <div class="col-md-6">
        <label for="age" class="form-label">Age</label>
        <input type="text" id="age" name="age" placeholder="Enter your age" class="form-control" value="<?= $profile['age'] ?? '' ?>">
      </div>
      <div class="col-md-6">
        <label for="sex" class="form-label">Gender</label>
        <select name="sex" id="sex" class="form-control">
          <option value="">Select Gender</option>
          <option value="Male" <?= (isset($profile['sex']) && $profile['sex'] === 'Male') ? 'selected' : '' ?>>Male</option>
          <option value="Female" <?= (isset($profile['sex']) && $profile['sex'] === 'Female') ? 'selected' : '' ?>>Female</option>
          <option value="Other" <?= (isset($profile['sex']) && $profile['sex'] === 'Other') ? 'selected' : '' ?>>Other</option>
        </select>
      </div>
    </div>

    <div class="mb-2">
      <label for="profession" class="form-label">Profession</label>
      <input type="text" id="profession" name="profession" placeholder="Enter your profession" class="form-control" value="<?= $profile['profession'] ?? '' ?>">
    </div>

    <div class="mb-2">
      <label for="education" class="form-label">Education Level</label>
      <select name="education" id="education" class="form-control">
        <option value="">Select Education Level</option>
        <option value="Undergraduate" <?= (isset($profile['education']) && $profile['education'] === 'Undergraduate') ? 'selected' : '' ?>>Undergraduate</option>
        <option value="Graduate" <?= (isset($profile['education']) && $profile['education'] === 'Graduate') ? 'selected' : '' ?>>Graduate</option>
      </select>
    </div>

    <div class="mb-2">
      <label for="hobby" class="form-label">Hobby</label>
      <input type="text" id="hobby" name="hobby" placeholder="Enter your hobby" class="form-control" value="<?= $profile['hobby'] ?? '' ?>">
    </div>

    <div class="mb-3">
      <label for="about" class="form-label">About Your Work</label>
      <textarea id="about" name="about" placeholder="Tell us about your work" class="form-control"><?= $profile['about'] ?? '' ?></textarea>
    </div>

    <h5 class="mt-3">Personal Data</h5>

    <div class="mb-2">
      <label for="dob" class="form-label">Date of Birth</label>
      <input type="date" id="dob" name="dob" class="form-control" value="<?= $profile['dob'] ?? '' ?>">
    </div>

    <div class="mb-2">
      <label for="email" class="form-label">Email ID</label>
      <input type="email" id="email" name="email" placeholder="Enter your email" class="form-control" value="<?= $profile['email'] ?? '' ?>">
    </div>

    <div class="mb-2">
      <label for="phone" class="form-label">Phone Number</label>
      <input type="text" id="phone" name="phone" placeholder="Enter your phone number" class="form-control" value="<?= $profile['phone'] ?? '' ?>">
    </div>

    <div class="mb-2">
      <label for="address" class="form-label">Address</label>
      <input type="text" id="address" name="address" placeholder="Enter your address" class="form-control" value="<?= $profile['address'] ?? '' ?>">
    </div>

    <h5 class="mt-3">Preferences</h5>

    <div class="mb-2">
      <label for="likes" class="form-label">Likes</label>
      <input type="text" id="likes" name="likes" placeholder="What do you like?" class="form-control" value="<?= $profile['likes'] ?? '' ?>">
    </div>

    <div class="mb-3">
      <label for="dislikes" class="form-label">Dislikes</label>
      <input type="text" id="dislikes" name="dislikes" placeholder="What do you dislike?" class="form-control" value="<?= $profile['dislikes'] ?? '' ?>">
    </div>

    <button type="submit" class="btn btn-primary">Save Info</button>
  </form>

  <?php if ($profile): ?>
  <div class="mt-4">
    <h5>CV Preview:</h5>
    <div class="card p-4 border-start border-5 border-primary bg-light shadow" id="cv" style="border-radius: 12px;">
      <h4 class="text-primary">👤 <?= htmlspecialchars($user["name"]) ?>'s CV</h4>
      <hr>
      <div class="row">
        <div class="col-md-6">
          <p><strong>Age:</strong> <?= $profile["age"] ?></p>
          <p><strong>Sex:</strong> <?= $profile["sex"] ?></p>
          <p><strong>Date of Birth:</strong> <?= $profile["dob"] ?></p>
          <p><strong>Profession:</strong> <?= $profile["profession"] ?></p>
          <p><strong>Education:</strong> <?= $profile["education"] ?></p>
          <p><strong>Hobby:</strong> <?= $profile["hobby"] ?></p>
        </div>
        <div class="col-md-6">
          <p><strong>Email:</strong> <?= $profile["email"] ?></p>
          <p><strong>Phone:</strong> <?= $profile["phone"] ?></p>
          <p><strong>Address:</strong> <?= $profile["address"] ?></p>
          <p><strong>Likes:</strong> <?= $profile["likes"] ?></p>
          <p><strong>Dislikes:</strong> <?= $profile["dislikes"] ?></p>
        </div>
      </div>
      <div class="mt-3">
        <p><strong>About your work:</strong></p>
        <div class="p-3 bg-white rounded border shadow-sm"> <?= nl2br($profile["about"]) ?> </div>
      </div>
    </div>
    <button class="btn btn-outline-primary mt-3" onclick="printCV()">🖨️ Print CV</button>
  </div>
<?php endif; ?>
</section>

<!-- 🌐 Web Search Section -->
<section id="websearch" class="mb-5">
  <h3 class="mt-5">🌐 Web Search</h3>
  <div class="card shadow-sm border-primary border-start border-4">
    <div class="card-body">
      <p class="mb-3">Use the box below to search the web directly from your dashboard.</p>
      <!-- Google Programmable Search Engine -->
       <script async src="https://cse.google.com/cse.js?cx=6070f5577a8374023"></script>
      <div class="gcse-search" data-linktarget="_blank"></div>
    </div>
  </div>
</section>


       <!-- Schedule Section -->
      <section id="schedule" class="mb-5">
        <h3 class="mt-5">Add Schedule</h3>
        <form method="POST" action="save_schedule.php" class="card p-4 shadow-sm mb-4">
          <div class="row mb-3">
            <div class="col">
              <input type="date" name="date" class="form-control" required>
            </div>
            <div class="col">
              <input type="time" name="time" class="form-control" required>
            </div>
          </div>
          <input type="text" name="event" class="form-control mb-3" placeholder="Event Description" required>
          <button type="submit" class="btn btn-success w-100">Add</button>
        </form>

        <h4>Your Schedules</h4>
        <input type="text" id="schedule-search" class="form-control mb-3" placeholder="Search schedules...">
        <ul class="list-group" id="schedule-list">
          <?php foreach ($schedules as $s): ?>
            <li class="list-group-item">
              <?= $s["event_date"] ?> @ <?= $s["event_time"] ?> - <?= htmlspecialchars($s["description"]) ?><br>
              <a href="delete_schedule.php?id=<?= $s['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this schedule?');">Delete</a>
           
            </li>
          <?php endforeach; ?>
        </ul>
      </section>

      
       <!-- Notes Section -->
      <section id="notes" class="mb-5">
        <h3 class="mt-5">Take a Note</h3>
        <form method="POST" action="save_note.php" class="card p-4 shadow-sm mb-4">
          <textarea name="note" class="form-control mb-3" placeholder="Write your meeting note or thought..." required></textarea>
          <button class="btn btn-info">Save Note</button>
        </form>

        <h4>Your Notes</h4>
        <input type="text" id="note-search" class="form-control mb-3" placeholder="Search notes...">
        <ul class="list-group" id="note-list">
          <?php foreach ($notes as $note): ?>
            <li class="list-group-item">
              <?= nl2br(htmlspecialchars($note['content'])) ?><br>
              <small class="text-muted"><?= $note['created_at'] ?></small><br>
              <a href="delete_note.php?id=<?= $note['id'] ?>" class="btn btn-sm btn-danger mt-2" onclick="return confirm('Are you sure you want to delete this note?');">Delete</a>
            </li>
          <?php endforeach; ?>
        </ul>
      </section>
      <!-- Files Section -->
      <section id="files" class="mb-5">
        <h3 class="mt-5">Your Files</h3>

        <!-- Upload Form -->
        <form method="POST" action="upload_file.php"  enctype="multipart/form-data" class="card p-4 shadow-sm mb-4">
          <div class="mb-3">
            <input type="file" name="file" class="form-control" required>
          </div>
          <button type="submit" class="btn btn-success">Upload</button>
        </form>

        <!-- File List -->
        <?php if (count($files) > 0): ?>
          <ul class="list-group">
            <?php foreach ($files as $file): ?>
              <li class="list-group-item d-flex justify-content-between align-items-center">
                <?= htmlspecialchars($file['filename']) ?>
                <div class="d-flex gap-2">
                  <a href="uploads/<?= htmlspecialchars($file['filename']) ?>" class="btn btn-sm btn-primary" download>Download</a>
                  <form action="update_file.php" method="POST" enctype="multipart/form-data" class="d-flex gap-1 align-items-center">
                    <input type="hidden" name="file_id" value="<?= $file['id'] ?>">
                    <input type="file" name="new_file" required class="form-control">
                    <button type="submit" class="btn btn-sm btn-warning">Update</button>
                  </form>
                  <form action="delete_file.php" method="POST">
                    <input type="hidden" name="file_id" value="<?= $file['id'] ?>">
                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this file?')">Delete</button>
                  </form>
                </div>
              </li>
            <?php endforeach; ?>
          </ul>
        <?php else: ?>
          <p>No files uploaded yet.</p>
        <?php endif; ?>
      </section>
      <h4>Search Schedule by Date</h4>
        <form method="GET" action="#schedule" class="mb-3">
          <div class="input-group">
            <input type="date" name="search_date" class="form-control" required>
            <button type="submit" class="btn btn-outline-primary">Search</button>
          </div>
        </form>

        <?php if (!empty($_GET['search_date'])): ?>
          <div class="card p-3 shadow-sm">
            <h5>Search Results for <?= htmlspecialchars($_GET['search_date']) ?>:</h5>
            <ul class="list-group">
              <?php if ($searchResults): ?>
                <?php foreach ($searchResults as $result): ?>
                  <li class="list-group-item"><?= $result['event_date'] ?> @ <?= $result['event_time'] ?> - <?= htmlspecialchars($result['description']) ?></li>
                <?php endforeach; ?>
              <?php else: ?>
                <li class="list-group-item">No schedules found for that date.</li>
              <?php endif; ?>
            </ul>
          </div>
        <?php endif; ?>
      </section>
      <!-- JS for Print -->
      <script>

        // When DOB changes, compute age and set the age field
  document.querySelector('input[name="dob"]').addEventListener('change', function() {
    const dobInput = this.value;          // e.g. "1990-05-18"
    if (!dobInput) return;
  
    const dob = new Date(dobInput);
    const today = new Date();
    let age = today.getFullYear() - dob.getFullYear();
    const m = today.getMonth() - dob.getMonth();
    // If birthday hasn't occurred yet this year, subtract 1
    if (m < 0 || (m === 0 && today.getDate() < dob.getDate())) {
      age--;
    }
  
    document.querySelector('input[name="age"]').value = age;
  });
        // Sidebar toggle
  document.getElementById('toggle-sidebar').addEventListener('click', function () {
    const sidebar = document.getElementById('sidebar');
    sidebar.classList.toggle('d-none');
  });

 
  // Notes Filter
  document.getElementById('note-search').addEventListener('input', function () {
    const keyword = this.value.toLowerCase();
    const list = document.getElementById('note-list').children;
    for (let item of list) {
      const text = item.innerText.toLowerCase();
      item.style.display = text.includes(keyword) ? '' : 'none';
    }
  });

  // Schedules Filter
  document.getElementById('schedule-search').addEventListener('input', function () {
    const keyword = this.value.toLowerCase();
    const list = document.getElementById('schedule-list').children;
    for (let item of list) {
      const text = item.innerText.toLowerCase();
      item.style.display = text.includes(keyword) ? '' : 'none';
    }
  });
        function printCV() {
          const content = document.getElementById('cv').innerHTML;
          const win = window.open('', '', 'height=600,width=800');
          win.document.write('<html><head><title>My CV</title>');
          win.document.write('<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">');
          win.document.write('</head><body><div class="container mt-5">');
          win.document.write(content);
          win.document.write('</div></body></html>');
          win.document.close();
          win.print();
        }
      </script>

    </div>
  </div>
</div>

<?php require 'footer.php'; ?>
