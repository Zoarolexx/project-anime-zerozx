<?php 
session_start();
require_once 'functions.php';

$slug = $_GET['slug'] ?? '';
$genre = getGenreBySlug($slug);
if (!$genre) die("<h1>404 - Genre tidak ditemukan</h1>");

$anime_list = getAnimeByGenre($slug);
$total_anime = count($anime_list);

addStat('page_master', 0, $_SERVER['REMOTE_ADDR']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Genre <?= $genre['title'] ?> - <?= SITE_NAME ?></title>
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
        <div class="page-header">
            <h2><i class="fas fa-tag"></i> Genre: <?= $genre['title'] ?></h2>
            <p>Menampilkan <?= $total_anime ?> anime dengan genre <?= $genre['title'] ?></p>
        </div>

        <div class="anime-grid">
            <?php if (empty($anime_list)): ?>
                <p class="no-data">Tidak ada anime dengan genre ini.</p>
            <?php else: ?>
                <?php foreach ($anime_list as $anime): ?>
                <div class="anime-card">
                    <a href="anime.php?slug=<?= $anime['seo_slug'] ?>">
                        <div class="card-img">
                            <img src="uploads/<?= $anime['thumb'] ?>" alt="<?= $anime['title'] ?>">
                            <span class="status <?= $anime['status'] ?>"><?= $anime['status'] ?></span>
                        </div>
                        <h4><?= $anime['title'] ?></h4>
                    </a>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </main>

    <footer>
        <p>&copy; <?= date('Y') ?> ZerozNime</p>
    </footer>
</body>
</html>