<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/panel.php';
requireLogin('admin');
$pdo=getPDO();
if(isset($_GET['delete'])){$stmt=$pdo->prepare('DELETE FROM gallery WHERE id=:id');$stmt->execute(['id'=>(int)$_GET['delete']]);flash('Image removed');redirect('admin/gallery.php');}
if($_SERVER['REQUEST_METHOD']==='POST'){
  if(!validateCsrfToken($_POST['csrf_token']??null)){flash('Invalid CSRF token','danger');redirect('admin/gallery.php');}
  try{$title=sanitizeString($_POST['title']??'');$path=handleUpload($_FILES['image'],ALLOWED_IMAGE_TYPES,'gallery');
    if(!$path){throw new RuntimeException('Image required.');}
    $stmt=$pdo->prepare('INSERT INTO gallery(title,image_path,created_by) VALUES(:title,:image_path,:created_by)');
    $stmt->execute(['title'=>$title,'image_path'=>$path,'created_by'=>(int)$_SESSION['user']['id']]);flash('Image uploaded');
  }catch(Throwable $e){logError($e->getMessage());flash($e->getMessage(),'danger');}
  redirect('admin/gallery.php');
}
$rows=$pdo->query('SELECT * FROM gallery ORDER BY id DESC')->fetchAll();
panelHeader('Manage Gallery','admin');
?>
<form method="post" enctype="multipart/form-data" class="card card-body mb-3"><input type="hidden" name="csrf_token" value="<?= e(csrfToken()) ?>"><input class="form-control mb-2" name="title" required placeholder="Image title"><input class="form-control mb-2" type="file" name="image" accept="image/*" required><button class="btn btn-primary">Upload</button></form>
<div class="row g-3"><?php foreach($rows as $r): ?><div class="col-md-3"><div class="card"><img class="card-img-top" src="<?= APP_URL ?>/serve-image.php?img=<?= urlencode($r['image_path']) ?>" alt=""><div class="card-body"><h6><?= e($r['title']) ?></h6><a onclick="return confirm('Delete?')" href="?delete=<?= (int)$r['id'] ?>">Delete</a></div></div></div><?php endforeach; ?></div>
<?php panelFooter(); ?>
