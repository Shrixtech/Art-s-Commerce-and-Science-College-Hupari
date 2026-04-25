<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/panel.php';
requireLogin('admin');
$pdo=getPDO();
if(isset($_GET['delete'])){$stmt=$pdo->prepare('DELETE FROM departments WHERE id=:id');$stmt->execute(['id'=>(int)$_GET['delete']]);flash('Department deleted');redirect('admin/departments.php');}
if($_SERVER['REQUEST_METHOD']==='POST'){
 if(!validateCsrfToken($_POST['csrf_token']??null)){flash('Invalid CSRF token','danger');redirect('admin/departments.php');}
 $id=(int)($_POST['id']??0);$name=sanitizeString($_POST['name']??'');$description=sanitizeString($_POST['description']??'');
 if($id>0){$stmt=$pdo->prepare('UPDATE departments SET name=:name,description=:description WHERE id=:id');$stmt->execute(compact('name','description','id'));flash('Updated');}
 else{$stmt=$pdo->prepare('INSERT INTO departments(name,description) VALUES(:name,:description)');$stmt->execute(compact('name','description'));flash('Created');}
 redirect('admin/departments.php');
}
$edit=null;if(isset($_GET['edit'])){$stmt=$pdo->prepare('SELECT * FROM departments WHERE id=:id');$stmt->execute(['id'=>(int)$_GET['edit']]);$edit=$stmt->fetch();}
$rows=$pdo->query('SELECT * FROM departments ORDER BY name')->fetchAll();
panelHeader('Manage Departments','admin');
?>
<form method="post" class="card card-body mb-3"><input type="hidden" name="csrf_token" value="<?= e(csrfToken()) ?>"><input type="hidden" name="id" value="<?= (int)($edit['id']??0) ?>"><input class="form-control mb-2" name="name" required value="<?= e($edit['name']??'') ?>" placeholder="Department"><textarea class="form-control mb-2" name="description" required><?= e($edit['description']??'') ?></textarea><button class="btn btn-primary">Save</button></form>
<table class="table table-bordered"><tr><th>Name</th><th>Actions</th></tr><?php foreach($rows as $r): ?><tr><td><?= e($r['name']) ?></td><td><a href="?edit=<?= (int)$r['id'] ?>">Edit</a> | <a href="?delete=<?= (int)$r['id'] ?>" onclick="return confirm('Delete?')">Delete</a></td></tr><?php endforeach; ?></table>
<?php panelFooter(); ?>
