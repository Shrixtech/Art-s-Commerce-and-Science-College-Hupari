<?php
declare(strict_types=1);
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/../config/db.php';

function panelHeader(string $title, string $role): void
{
    require __DIR__ . '/header.php';
    echo '<nav class="navbar navbar-expand-lg bg-dark navbar-dark"><div class="container-fluid">';
    echo '<a class="navbar-brand" href="' . APP_URL . '/' . $role . '/dashboard.php">' . ucfirst($role) . ' Panel</a>';
    echo '<div class="ms-auto d-flex gap-2"><span class="navbar-text text-white">' . e($_SESSION['user']['name'] ?? '') . '</span>';
    echo '<a href="' . APP_URL . '/' . $role . '/logout.php" class="btn btn-sm btn-outline-light">Logout</a></div></div></nav>';
    echo '<main class="container py-4"><h2 class="mb-3">' . e($title) . '</h2>';
    $flash = flash();
    if ($flash) {
        echo '<div class="alert alert-' . e($flash['type']) . '">' . e($flash['message']) . '</div>';
    }
}

function panelFooter(): void
{
    echo '</main>';
    require __DIR__ . '/footer.php';
}
