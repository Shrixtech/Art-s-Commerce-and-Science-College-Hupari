<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/panel.php';
requireLogin('staff');
$pdo=getPDO();
if($_SERVER['REQUEST_METHOD']==='POST'){
  if(!validateCsrfToken($_POST['csrf_token']??null)){flash('Invalid CSRF token','danger');redirect('staff/dashboard.php');}
  $type=$_POST['type']??'';$title=sanitizeString($_POST['title']??'');$body=sanitizeString($_POST['body']??'');$uid=(int)$_SESSION['user']['id'];
  if($type==='notice'){$stmt=$pdo->prepare('INSERT INTO notices(title,body,created_by) VALUES(:title,:body,:created_by)');$stmt->execute(['title'=>$title,'body'=>$body,'created_by'=>$uid]);flash('Notice added');}
  if($type==='event'){$date=$_POST['event_date']??date('Y-m-d');$loc=sanitizeString($_POST['location']??'Campus');$stmt=$pdo->prepare('INSERT INTO events(title,description,event_date,location,created_by) VALUES(:title,:description,:event_date,:location,:created_by)');$stmt->execute(['title'=>$title,'description'=>$body,'event_date'=>$date,'location'=>$loc,'created_by'=>$uid]);flash('Event added');}
  redirect('staff/dashboard.php');
}
$students=$pdo->query('SELECT enrollment_no,full_name,email FROM students ORDER BY id DESC LIMIT 25')->fetchAll();
panelHeader('Staff Dashboard','staff');
?>
<div class="row g-3">
<div class="col-lg-6"><div class="card card-body"><h5>Add Notice</h5><form method="post"><input type="hidden" name="csrf_token" value="<?= e(csrfToken()) ?>"><input type="hidden" name="type" value="notice"><input class="form-control mb-2" name="title" required placeholder="Title"><textarea class="form-control mb-2" name="body" required></textarea><button class="btn btn-primary">Publish Notice</button></form></div></div>
<div class="col-lg-6"><div class="card card-body"><h5>Add Event</h5><form method="post"><input type="hidden" name="csrf_token" value="<?= e(csrfToken()) ?>"><input type="hidden" name="type" value="event"><input class="form-control mb-2" name="title" required placeholder="Event title"><textarea class="form-control mb-2" name="body" required placeholder="Description"></textarea><input class="form-control mb-2" type="date" name="event_date"><input class="form-control mb-2" name="location" placeholder="Location" required><button class="btn btn-primary">Create Event</button></form></div></div>
</div>
<h4 class="mt-4">Students</h4>
<table class="table table-bordered"><tr><th>Enrollment</th><th>Name</th><th>Email</th></tr><?php foreach($students as $s): ?><tr><td><?= e($s['enrollment_no']) ?></td><td><?= e($s['full_name']) ?></td><td><?= e($s['email']) ?></td></tr><?php endforeach; ?></table>
<?php panelFooter(); ?>
