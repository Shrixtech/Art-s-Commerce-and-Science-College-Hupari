<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/panel.php';
requireLogin('admin');
$pdo=getPDO();
if(isset($_GET['delete'])){$stmt=$pdo->prepare('DELETE FROM events WHERE id=:id');$stmt->execute(['id'=>(int)$_GET['delete']]);flash('Event deleted');redirect('admin/events.php');}
if($_SERVER['REQUEST_METHOD']==='POST'){
 if(!validateCsrfToken($_POST['csrf_token']??null)){flash('Invalid CSRF token','danger');redirect('admin/events.php');}
 $id=(int)($_POST['id']??0);$title=sanitizeString($_POST['title']??'');$description=sanitizeString($_POST['description']??'');$event_date=$_POST['event_date']??date('Y-m-d');$location=sanitizeString($_POST['location']??'Campus');
 if($id>0){$stmt=$pdo->prepare('UPDATE events SET title=:title,description=:description,event_date=:event_date,location=:location WHERE id=:id');$stmt->execute(compact('title','description','event_date','location','id'));flash('Event updated');}
 else{$created_by=(int)$_SESSION['user']['id'];$stmt=$pdo->prepare('INSERT INTO events(title,description,event_date,location,created_by) VALUES(:title,:description,:event_date,:location,:created_by)');$stmt->execute(compact('title','description','event_date','location','created_by'));flash('Event added');}
 redirect('admin/events.php');
}
$edit=null;if(isset($_GET['edit'])){$stmt=$pdo->prepare('SELECT * FROM events WHERE id=:id');$stmt->execute(['id'=>(int)$_GET['edit']]);$edit=$stmt->fetch();}
$rows=$pdo->query('SELECT * FROM events ORDER BY event_date DESC')->fetchAll();
panelHeader('Manage Events','admin');
?>
<form method="post" class="card card-body mb-3"><input type="hidden" name="csrf_token" value="<?= e(csrfToken()) ?>"><input type="hidden" name="id" value="<?= (int)($edit['id']??0) ?>">
<div class="row g-2"><div class="col-md-6"><input class="form-control" name="title" required value="<?= e($edit['title']??'') ?>" placeholder="Title"></div><div class="col-md-6"><input class="form-control" name="location" required value="<?= e($edit['location']??'') ?>" placeholder="Location"></div><div class="col-md-6"><input class="form-control" type="date" name="event_date" value="<?= e($edit['event_date']??date('Y-m-d')) ?>"></div><div class="col-md-12"><textarea class="form-control" name="description" required><?= e($edit['description']??'') ?></textarea></div></div>
<button class="btn btn-primary mt-2"><?= $edit?'Update':'Add' ?> Event</button></form>
<table class="table table-bordered"><tr><th>Title</th><th>Date</th><th>Location</th><th>Actions</th></tr><?php foreach($rows as $r): ?><tr><td><?= e($r['title']) ?></td><td><?= e($r['event_date']) ?></td><td><?= e($r['location']) ?></td><td><a href="?edit=<?= (int)$r['id'] ?>">Edit</a> | <a onclick="return confirm('Delete?')" href="?delete=<?= (int)$r['id'] ?>">Delete</a></td></tr><?php endforeach; ?></table>
<?php panelFooter(); ?>
