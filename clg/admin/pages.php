<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/panel.php';
requireLogin('admin');
$pdo=getPDO();
if(isset($_GET['delete'])){$stmt=$pdo->prepare('DELETE FROM pages WHERE id=:id');$stmt->execute(['id'=>(int)$_GET['delete']]);flash('Page deleted');redirect('admin/pages.php');}
if($_SERVER['REQUEST_METHOD']==='POST'){
 if(!validateCsrfToken($_POST['csrf_token']??null)){flash('Invalid CSRF token','danger');redirect('admin/pages.php');}
 $id=(int)($_POST['id']??0);$slug=sanitizeString($_POST['slug']??'');$title=sanitizeString($_POST['title']??'');$content=trim((string)($_POST['content']??''));
 if($id>0){$stmt=$pdo->prepare('UPDATE pages SET slug=:slug,title=:title,content=:content WHERE id=:id');$stmt->execute(compact('slug','title','content','id'));flash('Page updated');}
 else{$stmt=$pdo->prepare('INSERT INTO pages(slug,title,content) VALUES(:slug,:title,:content)');$stmt->execute(compact('slug','title','content'));flash('Page created');}
 redirect('admin/pages.php');
}
$edit=null;if(isset($_GET['edit'])){$stmt=$pdo->prepare('SELECT * FROM pages WHERE id=:id');$stmt->execute(['id'=>(int)$_GET['edit']]);$edit=$stmt->fetch();}
$rows=$pdo->query('SELECT id,slug,title,updated_at FROM pages ORDER BY updated_at DESC')->fetchAll();
panelHeader('Pages CMS','admin');
?>
<form method="post" class="card card-body mb-3"><input type="hidden" name="csrf_token" value="<?= e(csrfToken()) ?>"><input type="hidden" name="id" value="<?= (int)($edit['id']??0) ?>"><input class="form-control mb-2" name="slug" required placeholder="Slug" value="<?= e($edit['slug']??'') ?>"><input class="form-control mb-2" name="title" required placeholder="Title" value="<?= e($edit['title']??'') ?>"><textarea class="form-control mb-2" rows="4" name="content" required><?= e($edit['content']??'') ?></textarea><button class="btn btn-primary">Save</button></form>
<table class="table table-bordered"><tr><th>Slug</th><th>Title</th><th>Updated</th><th>Actions</th></tr><?php foreach($rows as $r): ?><tr><td><?= e($r['slug']) ?></td><td><?= e($r['title']) ?></td><td><?= e($r['updated_at']) ?></td><td><a href="?edit=<?= (int)$r['id'] ?>">Edit</a> | <a href="?delete=<?= (int)$r['id'] ?>" onclick="return confirm('Delete?')">Delete</a></td></tr><?php endforeach; ?></table>
<?php panelFooter(); ?>
