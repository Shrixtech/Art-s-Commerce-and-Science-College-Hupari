<?php
declare(strict_types=1);
require_once __DIR__ . '/../config/constants.php';

function e(?string $value): string
{
    return htmlspecialchars((string)$value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function logError(string $message): void
{
    $entry = sprintf("[%s] %s%s", date('Y-m-d H:i:s'), $message, PHP_EOL);
    @file_put_contents(LOG_FILE, $entry, FILE_APPEND);
}

function sanitizeString(?string $value): string
{
    return trim((string)filter_var($value, FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW));
}

function csrfToken(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function validateCsrfToken(?string $token): bool
{
    return isset($_SESSION['csrf_token']) && is_string($token) && hash_equals($_SESSION['csrf_token'], $token);
}

function redirect(string $path): never
{
    header('Location: ' . APP_URL . '/' . ltrim($path, '/'));
    exit;
}

function handleUpload(array $file, array $allowedMime, string $subDir = 'images'): ?string
{
    if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
        return null;
    }
    if (($file['size'] ?? 0) > MAX_UPLOAD_SIZE) {
        throw new RuntimeException('File exceeds size limit.');
    }
    $tmp = $file['tmp_name'] ?? '';
    $mime = mime_content_type($tmp);
    if (!in_array($mime, $allowedMime, true)) {
        throw new RuntimeException('Invalid file type.');
    }
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $name = bin2hex(random_bytes(16)) . '.' . $ext;
    $relative = $subDir . '/' . $name;
    $targetDir = __DIR__ . '/../uploads/' . $subDir;
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0755, true);
    }
    $target = $targetDir . '/' . $name;
    if (!move_uploaded_file($tmp, $target)) {
        throw new RuntimeException('Upload failed.');
    }
    return $relative;
}

function flash(?string $message = null, string $type = 'success'): ?array
{
    if ($message !== null) {
        $_SESSION['flash'] = ['message' => $message, 'type' => $type];
        return null;
    }
    $item = $_SESSION['flash'] ?? null;
    unset($_SESSION['flash']);
    return $item;
}

function currentTheme(): string
{
    return $_COOKIE['theme'] ?? 'theme-default';
}
