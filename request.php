<?php 
session_start();
require_once 'functions.php';

$message = '';
$error = '';

if ($_POST['submit']) {
    $email = $_POST['email'] ?? '';
    $pesan = $_POST['pesan'] ?? '';
    
    if (empty($email) || empty($pesan)) {
        $error = 'Email dan pesan request harus diisi!';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Email tidak valid!';
    } else {
        if (addRequest($email, $pesan)) {
            $message = 'Request berhasil dikirim! Admin akan memprosesnya.';
        } else {
            $error = 'Gagal mengirim request. Silakan coba lagi.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Request Anime - <?= SITE_NAME ?></title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <div class="logo">
                <a href="index.php"><h1>Zeroz<span>Nime</span></h1></a>
            </div>
            <a href="javascript:history.back()" class="back-btn"><i class="fas fa-arrow-left"></i> Kembali</a>
        </div>
    </nav>

    <main class="container">
        <div class="request-wrapper">
            <div class="request-header">
                <i class="fas fa-envelope-open-text"></i>
                <h2>Request Anime</h2>
                <p>Anime yang kamu mau belum tersedia? Request di sini ya!</p>
            </div>
            
            <?php if ($message): ?>
                <div class="alert success"><i class="fas fa-check-circle"></i> <?= $message ?></div>
            <?php endif; ?>
            
            <?php if ($error): ?>
                <div class="alert error"><i class="fas fa-exclamation-triangle"></i> <?= $error ?></div>
            <?php endif; ?>
            
            <form method="post" class="request-form">
                <div class="form-group">
                    <label><i class="fas fa-envelope"></i> Email</label>
                    <input type="email" name="email" placeholder="email@example.com" required>
                </div>
                <div class="form-group">
                    <label><i class="fas fa-tv"></i> Request Anime</label>
                    <textarea name="pesan" rows="5" placeholder="Contoh: One Piece Season 2 / Movie Spy x Family / dll" required></textarea>
                </div>
                <button type="submit" name="submit" class="btn-primary">
                    <i class="fas fa-paper-plane"></i> Kirim Request
                </button>
            </form>
        </div>
    </main>

    <footer>
        <p>&copy; <?= date('Y') ?> ZerozNime</p>
    </footer>
</body>
</html>