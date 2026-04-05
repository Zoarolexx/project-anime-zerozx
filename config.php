<?php
// config.php - Database + Setting Website

// ========== DATABASE ==========
$host = 'localhost';
$user = 'root';
$pass = '';
$db   = 'zeroznime_db';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) die("Koneksi gagal: " . $conn->connect_error);

// ========== WEBSITE SETTINGS ==========
define('SITE_NAME', 'ZerozNime');
define('SITE_URL', 'http://localhost/zeroznime_complete/'); // GANTI PAKE DOMAIN LO
define('SITE_DESCRIPTION', 'ZerozNime - Streaming anime subtitle Indonesia gratis, berbagai anime movie series HD tanpa iklan.');
define('SITE_KEYWORDS', 'zeroznime, anime, streaming anime, nonton anime, anime sub indo');
define('ADMIN_EMAIL', 'admin@zeroznime.com');

// ========== UPLOAD SETTINGS ==========
define('MAX_FILE_SIZE', 5242880); // 5MB
define('ALLOWED_EXTENSIONS', serialize(['jpg', 'jpeg', 'png', 'gif', 'webp']));

// ========== THEME ==========
$theme_dark = isset($_COOKIE['theme_dark']) ? $_COOKIE['theme_dark'] : 'false';

// ========== PAGINATION ==========
define('PER_PAGE', 12);

// ========== SOCIAL MEDIA ==========
$social = [
    'facebook' => 'https://facebook.com/zeroznime',
    'twitter' => 'https://twitter.com/zeroznime',
    'instagram' => 'https://instagram.com/zeroznime',
    'youtube' => 'https://youtube.com/@zeroznime'
];

// ========== MAILER (untuk request) ==========
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_USER', '');
define('SMTP_PASS', '');
define('SMTP_FROM', 'noreply@zeroznime.com');
?>