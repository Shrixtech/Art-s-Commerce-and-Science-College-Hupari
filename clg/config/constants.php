<?php
declare(strict_types=1);

/**
 * Core application constants.
 * Keep these values environment-safe for local XAMPP usage.
 */
const APP_NAME = 'Art, Commerce and Science College Hupari';
const APP_ENV = 'development';
const APP_URL = 'http://localhost/clg';
const APP_TIMEZONE = 'Asia/Kolkata';

/**
 * Database configuration.
 */
const DB_HOST = '127.0.0.1';
const DB_PORT = 3306;
const DB_NAME = 'clg_cms';
const DB_USER = 'root';
const DB_PASS = '';

/**
 * Security and session configuration.
 */
const SESSION_TIMEOUT = 1800; // 30 minutes
const SESSION_NAME = 'clg_session';
const CSRF_TOKEN_KEY = 'csrf_token';

/**
 * Upload controls.
 */
const MAX_UPLOAD_SIZE = 3 * 1024 * 1024;
const ALLOWED_IMAGE_TYPES = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
const ALLOWED_DOC_TYPES = [
    'application/pdf',
    'application/msword',
    'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
];

/**
 * Logging.
 */
const LOG_FILE = __DIR__ . '/../logs/app.log';

if (!defined('DATE_TIMEZONE_SET')) {
    date_default_timezone_set(APP_TIMEZONE);
    define('DATE_TIMEZONE_SET', true);
}
