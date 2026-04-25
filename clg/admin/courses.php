<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/panel.php';
requireLogin('admin');
$pdo=getPDO();
if(isset($_GET['delete'])){$stmt=$pdo->prepare('DELETE FROM courses WHERE id=:id');$stmt->execute(['id'=>(int)$_GET['delete']]);flash('Course deleted');redirect('admin/courses.php');}
if($_SERVER['REQUEST_METHOD']==='POST'){
 if(!validateCsrfToken($_POST['csrf_token']??null)){flash('Invalid CSRF token','danger');redirect('admin/courses.php');}
 $id=(int)($_POST['id']??0);$department_id=(int)($_POST['department_id']??0);$name=sanitizeString($_POST['name']??'');$duration=sanitizeString($_POST['duration']??'');
 if($id>0){$stmt=$pdo->prepare('UPDATE courses SET department_id=:department_id,name=:name,duration=:duration WHERE id=:id');$stmt->execute(compact('department_id','name','duration','id'));flash('Updated');}
 else{$stmt=$pdo->prepare('INSERT INTO courses(department_id,name,duration) VALUES(:department_id,:name,:duration)');$stmt->execute(compact('department_id','name','duration'));flash('Created');}
 redirect('admin/courses.php');
}
$edit=null;if(isset($_GET['edit'])){$stmt=$pdo->prepare('SELECT * FROM courses WHERE id=:id');$stmt->execute(['id'=>(int)$_GET['edit']]);$edit=$stmt->fetch();}
$departments=$pdo->query('SELECT id,name FROM departments ORDER BY name')->fetchAll();
$rows=$pdo->query('SELECT c.*,d.name AS department FROM courses c LEFT JOIN departments d ON d.id=c.department_id ORDER BY c.id DESC')->fetchAll();
panelHeader('Manage Courses','admin');
?>
<form method="post" class="card card-body mb-3"><input type="hidden" name="csrf_token" value="<?= e(csrfToken()) ?>"><input type="hidden" name="id" value="<?= (int)($edit['id']??0) ?>"><div class="row g-2"><div class="col-md-4"><select class="form-select" name="department_id" required><?php foreach($departments as $d): ?><option value="<?= (int)$d['id'] ?>" <?= ((int)($edit['department_id']??0)===(int)$d['id'])?'selected':'' ?>><?= e($d['name']) ?></option><?php endforeach; ?></select></div><div class="col-md-4"><input class="form-control" name="name" required value="<?= e($edit['name']??'') ?>" placeholder="Course"></div><div class="col-md-4"><input class="form-control" name="duration" required value="<?= e($edit['duration']??'') ?>" placeholder="Duration"></div></div><button class="btn btn-primary mt-2">Save</button></form>
<table class="table table-bordered"><tr><th>Course</th><th>Department</th><th>Duration</th><th>Actions</th></tr><?php foreach($rows as $r): ?><tr><td><?= e($r['name']) ?></td><td><?= e($r['department']) ?></td><td><?= e($r['duration']) ?></td><td><a href="?edit=<?= (int)$r['id'] ?>">Edit</a> | <a href="?delete=<?= (int)$r['id'] ?>" onclick="return confirm('Delete?')">Delete</a></td></tr><?php endforeach; ?></table>
<?php panelFooter(); ?>
