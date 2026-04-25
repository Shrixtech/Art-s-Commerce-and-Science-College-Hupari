<?php
declare(strict_types=1);
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/header.php';

$pdo = getPDO();
$notices = $pdo->query('SELECT id, title, published_at FROM notices ORDER BY published_at DESC LIMIT 6')->fetchAll();
$events = $pdo->query('SELECT id, title, event_date, location FROM events ORDER BY event_date DESC LIMIT 6')->fetchAll();
$gallery = $pdo->query('SELECT id, title, image_path FROM gallery ORDER BY id DESC LIMIT 8')->fetchAll();
?>
<div class="top-header py-2 bg-dark text-light small">
  <div class="container d-flex justify-content-between"><span>Email: info@huparicollege.edu</span><span>Call: +91 12345 67890</span></div>
</div>
<header class="py-3 border-bottom">
  <div class="container d-flex align-items-center gap-3">
    <img src="<?= APP_URL ?>/assets/images/logo.svg" alt="logo" width="56">
    <div><h1 class="h4 mb-0">Art's Commerce and Science College Hupari</h1><small>Knowledge. Character. Service.</small></div>
  </div>
</header>
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
  <div class="container">
    <a class="navbar-brand" href="#">ACSCH</a>
    <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#mainNav"><span class="navbar-toggler-icon"></span></button>
    <div class="collapse navbar-collapse" id="mainNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="#about">About</a></li>
        <li class="nav-item dropdown"><a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#">Academics</a><ul class="dropdown-menu"><li><a class="dropdown-item" href="#">Departments</a></li><li><a class="dropdown-item" href="#">Courses</a></li></ul></li>
        <li class="nav-item"><a class="nav-link" href="#news">News</a></li>
        <li class="nav-item"><a class="nav-link" href="#gallery">Gallery</a></li>
      </ul>
    </div>
  </div>
</nav>
<section class="hero-section d-flex align-items-center">
  <div class="overlay"></div>
  <div class="container position-relative text-white" data-aos="fade-up">
    <h2 class="display-5 fw-bold">Shape Your Future With Excellence</h2>
    <p class="lead">Admissions open for undergraduate and postgraduate programs.</p>
    <a class="btn btn-warning" href="#quick-links">Explore Quick Links</a>
  </div>
</section>
<section class="py-5">
  <div class="container">
    <h3 class="mb-4">Management</h3>
    <div class="row g-4">
      <?php foreach (['Chairman', 'Principal', 'Vice Principal'] as $role): ?>
      <div class="col-md-4" data-aos="zoom-in"><div class="card h-100"><div class="card-body"><h5><?= e($role) ?></h5><p class="text-muted">Committed to quality education and growth.</p></div></div></div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<section id="about" class="py-5 bg-light">
  <div class="container"><h3>About Us</h3><p>ACSCH is a dynamic institute blending arts, commerce, and science with industry-aligned learning.</p></div>
</section>
<section id="news" class="py-5">
  <div class="container">
    <div class="row g-4">
      <div class="col-lg-6"><h3>Notices</h3><ul class="list-group"><?php foreach ($notices as $n): ?><li class="list-group-item d-flex justify-content-between"><span><?= e($n['title']) ?></span><small><?= e($n['published_at']) ?></small></li><?php endforeach; ?></ul></div>
      <div class="col-lg-6"><h3>Events</h3><ul class="list-group"><?php foreach ($events as $ev): ?><li class="list-group-item"><strong><?= e($ev['title']) ?></strong><br><small><?= e($ev['event_date']) ?> • <?= e($ev['location']) ?></small></li><?php endforeach; ?></ul></div>
    </div>
  </div>
</section>
<section id="gallery" class="py-5 bg-light">
  <div class="container"><h3>Gallery</h3><div class="row g-3">
  <?php foreach ($gallery as $img): ?>
  <div class="col-6 col-md-3"><a class="glightbox" href="<?= APP_URL ?>/serve-image.php?img=<?= urlencode($img['image_path']) ?>"><img loading="lazy" class="img-fluid rounded" src="<?= APP_URL ?>/serve-image.php?img=<?= urlencode($img['image_path']) ?>" alt="<?= e($img['title']) ?>"></a></div>
  <?php endforeach; ?>
  </div></div>
</section>
<section class="py-5 video-section text-white">
  <div class="container text-center" data-aos="fade-up"><h3>Campus Tour</h3><a href="https://www.youtube.com/watch?v=dQw4w9WgXcQ" class="btn btn-outline-light glightbox">Watch Video</a></div>
</section>
<section id="quick-links" class="py-5">
  <div class="container"><h3>Quick Links</h3><div class="d-flex flex-wrap gap-2"><a class="btn btn-primary" href="<?= APP_URL ?>/admin/login.php">Admin Login</a><a class="btn btn-secondary" href="<?= APP_URL ?>/staff/login.php">Staff Login</a><a class="btn btn-success" href="<?= APP_URL ?>/student/login.php">Student Login</a></div></div>
</section>
<footer class="py-4 bg-dark text-light"><div class="container d-flex justify-content-between align-items-center"><span>&copy; <?= date('Y') ?> ACSCH</span><div class="d-flex gap-2"><button class="btn btn-sm btn-outline-light theme-btn" data-theme="theme-default">Default</button><button class="btn btn-sm btn-outline-light theme-btn" data-theme="theme-emerald">Emerald</button><button class="btn btn-sm btn-outline-light theme-btn" data-theme="theme-royal">Royal</button></div></div></footer>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
