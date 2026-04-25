<?php
declare(strict_types=1);
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/auth.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validateCsrfToken($_POST['csrf_token'] ?? null)) {
        $error = 'Invalid request.';
    } else {
        $email = sanitizeString($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $stmt = getPDO()->prepare('SELECT id, full_name, email, password_hash FROM admin_users WHERE email = :email LIMIT 1');
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch();
        if ($user && password_verify($password, $user['password_hash'])) {
            loginUser($user, 'admin');
            redirect('admin/dashboard.php');
        }
        $error = 'Invalid credentials';
    }
}
$title = 'Admin Login';
require __DIR__ . '/../includes/header.php';
?>
<div class="container py-5" style="max-width:460px">
  <h3>Admin Login</h3>
  <?php if ($error): ?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?>
  <form method="post" class="card card-body">
    <input type="hidden" name="csrf_token" value="<?= e(csrfToken()) ?>">
    <input class="form-control mb-3" type="email" name="email" placeholder="Email" required>
    <input class="form-control mb-3" type="password" name="password" placeholder="Password" required>
    <button class="btn btn-primary">Login</button>
  </form>
</div>
<?php require __DIR__ . '/../includes/footer.php'; ?>
