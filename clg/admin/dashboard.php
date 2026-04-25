<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/panel.php';
requireLogin('admin');
$pdo = getPDO();
$stats = [
  'staff' => (int)$pdo->query('SELECT COUNT(*) FROM staff_users')->fetchColumn(),
  'students' => (int)$pdo->query('SELECT COUNT(*) FROM students')->fetchColumn(),
  'notices' => (int)$pdo->query('SELECT COUNT(*) FROM notices')->fetchColumn(),
  'events' => (int)$pdo->query('SELECT COUNT(*) FROM events')->fetchColumn(),
];
panelHeader('Dashboard', 'admin');
?>
<div class="row g-3 mb-4">
  <?php foreach ($stats as $k=>$v): ?><div class="col-md-3"><div class="card card-body"><h5 class="text-capitalize"><?= e($k) ?></h5><p class="display-6 mb-0"><?= $v ?></p></div></div><?php endforeach; ?>
</div>
<div class="d-flex flex-wrap gap-2">
  <a class="btn btn-outline-primary" href="notices.php">Manage Notices</a>
  <a class="btn btn-outline-primary" href="events.php">Manage Events</a>
  <a class="btn btn-outline-primary" href="gallery.php">Manage Gallery</a>
  <a class="btn btn-outline-primary" href="departments.php">Manage Departments</a>
  <a class="btn btn-outline-primary" href="courses.php">Manage Courses</a>
  <a class="btn btn-outline-primary" href="pages.php">Pages CMS</a>
  <a class="btn btn-outline-secondary" href="logs.php">Logs Viewer</a>
</div>
<?php panelFooter(); ?>
