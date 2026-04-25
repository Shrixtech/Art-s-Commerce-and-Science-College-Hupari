<?php
declare(strict_types=1);
require_once __DIR__ . '/functions.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'secure' => false,
        'httponly' => true,
        'samesite' => 'Lax'
    ]);
    session_start();
}

function requireLogin(string $role): void
{
    if (empty($_SESSION['user']) || ($_SESSION['user']['role'] ?? '') !== $role) {
        redirect($role . '/login.php');
    }

    if ((time() - ($_SESSION['last_activity'] ?? 0)) > SESSION_TIMEOUT) {
        session_unset();
        session_destroy();
        redirect($role . '/login.php?timeout=1');
    }
    $_SESSION['last_activity'] = time();
}

function loginUser(array $user, string $role): void
{
    session_regenerate_id(true);
    $_SESSION['user'] = [
        'id' => (int)$user['id'],
        'name' => $user['full_name'],
        'email' => $user['email'],
        'role' => $role,
    ];
    $_SESSION['last_activity'] = time();
}

function logoutUser(): never
{
    session_unset();
    session_destroy();
    redirect('index.php');
}
