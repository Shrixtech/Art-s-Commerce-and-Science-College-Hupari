<?php
declare(strict_types=1);
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/auth.php';
$error='';
if($_SERVER['REQUEST_METHOD']==='POST'){
 if(!validateCsrfToken($_POST['csrf_token']??null)){$error='Invalid request.';}else{
  $email=sanitizeString($_POST['email']??'');$password=$_POST['password']??'';
  $stmt=getPDO()->prepare('SELECT id,full_name,email,password_hash FROM students WHERE email=:email LIMIT 1');$stmt->execute(['email'=>$email]);$u=$stmt->fetch();
  if($u && password_verify($password,$u['password_hash'])){loginUser($u,'student');redirect('student/dashboard.php');}
  $error='Invalid credentials';
 }
}
$title='Student Login';require __DIR__ . '/../includes/header.php'; ?>
<div class="container py-5" style="max-width:460px"><h3>Student Login</h3><?php if($error): ?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?><form method="post" class="card card-body"><input type="hidden" name="csrf_token" value="<?= e(csrfToken()) ?>"><input class="form-control mb-2" type="email" name="email" required><input class="form-control mb-2" type="password" name="password" required><button class="btn btn-success">Login</button></form></div>
<?php require __DIR__ . '/../includes/footer.php'; ?>
