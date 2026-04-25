<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/panel.php';
requireLogin('admin');
$pdo=getPDO();
if (isset($_GET['delete'])) {
  $stmt=$pdo->prepare('DELETE FROM notices WHERE id=:id');$stmt->execute(['id'=>(int)$_GET['delete']]);flash('Notice deleted');redirect('admin/notices.php');
}
if ($_SERVER['REQUEST_METHOD']==='POST') {
  if(!validateCsrfToken($_POST['csrf_token']??null)){flash('Invalid CSRF token','danger');redirect('admin/notices.php');}
  $id=(int)($_POST['id']??0);$title=sanitizeString($_POST['title']??'');$body=sanitizeString($_POST['body']??'');
  if($id>0){$stmt=$pdo->prepare('UPDATE notices SET title=:title, body=:body WHERE id=:id');$stmt->execute(compact('title','body','id'));flash('Notice updated');}
  else{$staffId=(int)($_SESSION['user']['id']??1);$stmt=$pdo->prepare('INSERT INTO notices(title,body,created_by) VALUES(:title,:body,:created_by)');$stmt->execute(['title'=>$title,'body'=>$body,'created_by'=>$staffId]);flash('Notice added');}
  redirect('admin/notices.php');
}
$edit=null;if(isset($_GET['edit'])){$stmt=$pdo->prepare('SELECT * FROM notices WHERE id=:id');$stmt->execute(['id'=>(int)$_GET['edit']]);$edit=$stmt->fetch();}
$rows=$pdo->query('SELECT id,title,published_at FROM notices ORDER BY published_at DESC')->fetchAll();
panelHeader('Manage Notices','admin');
?>
<form method="post" class="card card-body mb-3">
  <input type="hidden" name="csrf_token" value="<?= e(csrfToken()) ?>"><input type="hidden" name="id" value="<?= (int)($edit['id']??0) ?>">
  <input class="form-control mb-2" name="title" required placeholder="Notice title" value="<?= e($edit['title']??'') ?>">
  <textarea class="form-control mb-2" name="body" required placeholder="Notice details"><?= e($edit['body']??'') ?></textarea>
  <button class="btn btn-primary"><?= $edit?'Update':'Add' ?> Notice</button>
</form>
<table class="table table-bordered"><tr><th>Title</th><th>Date</th><th>Actions</th></tr><?php foreach($rows as $r): ?><tr><td><?= e($r['title']) ?></td><td><?= e($r['published_at']) ?></td><td><a href="?edit=<?= (int)$r['id'] ?>">Edit</a> | <a onclick="return confirm('Delete?')" href="?delete=<?= (int)$r['id'] ?>">Delete</a></td></tr><?php endforeach; ?></table>
<?php panelFooter(); ?>
