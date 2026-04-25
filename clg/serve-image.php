<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/functions.php';

$img = $_GET['img'] ?? '';
$img = str_replace('..', '', $img);
$base = realpath(__DIR__ . '/uploads');
$path = realpath(__DIR__ . '/uploads/' . ltrim($img, '/'));

if (!$path || !$base || !str_starts_with($path, $base) || !is_file($path)) {
    http_response_code(404);
    exit('Image not found');
}

$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mime = finfo_file($finfo, $path) ?: 'application/octet-stream';
finfo_close($finfo);
if (!in_array($mime, ALLOWED_IMAGE_TYPES, true)) {
    http_response_code(403);
    exit('Forbidden');
}

$lastModified = gmdate('D, d M Y H:i:s', filemtime($path)) . ' GMT';
$etag = '"' . md5_file($path) . '"';
header('Content-Type: ' . $mime);
header('Cache-Control: public, max-age=604800');
header('ETag: ' . $etag);
header('Last-Modified: ' . $lastModified);
readfile($path);
