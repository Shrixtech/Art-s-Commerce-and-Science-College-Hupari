<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/panel.php';
requireLogin('student');
$pdo=getPDO();
$uid=(int)$_SESSION['user']['id'];
$stmt=$pdo->prepare('SELECT s.*, d.name AS department_name, c.name AS course_name FROM students s LEFT JOIN departments d ON d.id=s.department_id LEFT JOIN courses c ON c.id=s.course_id WHERE s.id=:id');
$stmt->execute(['id'=>$uid]);$profile=$stmt->fetch();
$notices=$pdo->query('SELECT title,published_at FROM notices ORDER BY published_at DESC LIMIT 8')->fetchAll();
$events=$pdo->query('SELECT title,event_date,location FROM events ORDER BY event_date DESC LIMIT 8')->fetchAll();
$downloads=$pdo->query('SELECT title,file_path FROM materials ORDER BY id DESC LIMIT 8')->fetchAll();
panelHeader('Student Dashboard','student');
?>
<div class="card card-body mb-4"><h5>Profile</h5><div class="row"><div class="col-md-4"><strong>Name:</strong> <?= e($profile['full_name']??'') ?></div><div class="col-md-4"><strong>Enrollment:</strong> <?= e($profile['enrollment_no']??'') ?></div><div class="col-md-4"><strong>Course:</strong> <?= e($profile['course_name']??'') ?></div></div></div>
<div class="row g-3"><div class="col-lg-4"><h5>Notices</h5><ul class="list-group"><?php foreach($notices as $n): ?><li class="list-group-item"><strong><?= e($n['title']) ?></strong><br><small><?= e($n['published_at']) ?></small></li><?php endforeach; ?></ul></div><div class="col-lg-4"><h5>Events</h5><ul class="list-group"><?php foreach($events as $e): ?><li class="list-group-item"><strong><?= e($e['title']) ?></strong><br><small><?= e($e['event_date']) ?>, <?= e($e['location']) ?></small></li><?php endforeach; ?></ul></div><div class="col-lg-4"><h5>Downloads</h5><ul class="list-group"><?php foreach($downloads as $d): ?><li class="list-group-item"><a href="<?= APP_URL ?>/serve-image.php?img=<?= urlencode($d['file_path']) ?>" target="_blank"><?= e($d['title']) ?></a></li><?php endforeach; ?></ul></div></div>
<?php panelFooter(); ?>
