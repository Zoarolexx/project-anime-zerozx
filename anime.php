<?php 
session_start();
require_once 'functions.php';

$slug = $_GET['slug'] ?? '';
$anime = getAnimeBySlug($slug);
if (!$anime) die("<h1>404 - Anime tidak ditemukan</h1>");

$episodes = getEpisodes($anime['id']);
$total_eps = count($episodes);
$genres = explode(',', $anime['genre']);

addStat('anime_list', $anime['id'], $_SERVER['REMOTE_ADDR']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $anime['title'] ?> - <?= SITE_NAME ?></title>
    <meta name="description" content="<?= $anime['seo_description'] ?: truncate(strip_tags($anime['description']), 160) ?>">
    <meta name="keywords" content="<?= $anime['seo_keywords'] ?: $anime['title'] . ', nonton anime, streaming anime' ?>">
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body class="<?= $GLOBALS['theme_dark'] == 'true' ? 'dark-mode' : '' ?>">
    
    <nav class="navbar">
        <div class="container">
            <div class="logo">
                <a href="index.php">
                    <i class="fas fa-film"></i>
                    <h1>Zeroz<span>Nime</span></h1>
                </a>
            </div>
            <a href="javascript:history.back()" class="back-btn"><i class="fas fa-arrow-left"></i> Kembali</a>
        </div>
    </nav>

    <main class="container">
        <div class="detail-wrapper">
            <div class="detail-thumb">
                <img src="uploads/<?= $anime['thumb'] ?>" alt="<?= $anime['title'] ?>">
            </div>
            <div class="detail-info">
                <h2><?= $anime['title'] ?></h2>
                <div class="meta">
                    <span class="badge-type"><?= $anime['type_name'] ?? 'TV' ?></span>
                    <span class="badge status-<?= $anime['status'] ?>">
                        <?= $anime['status'] == 'selesai' ? 'Completed' : ($anime['status'] == 'sedang' ? 'Ongoing' : 'Upcoming') ?>
                    </span>
                    <span class="eps-info"><i class="fas fa-play-circle"></i> <?= $total_eps ?> Episode</span>
                </div>
                <div class="genre-list">
                    <?php foreach ($genres as $g): ?>
                        <a href="genre.php?slug=<?= createSlug(trim($g)) ?>" class="genre-tag"><?= trim($g) ?></a>
                    <?php endforeach; ?>
                </div>
                <p class="description"><?= nl2br($anime['description']) ?></p>
                
                <?php if ($anime['trailer']): ?>
                <div class="trailer">
                    <h4><i class="fab fa-youtube"></i> Trailer</h4>
                    <iframe src="https://www.youtube.com/embed/<?= $anime['trailer'] ?>" frameborder="0" allowfullscreen></iframe>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="episode-section">
            <h3><i class="fas fa-list"></i> Daftar Episode</h3>
            <div class="episode-grid">
                <?php if (empty($episodes)): ?>
                    <p class="no-episode">Belum ada episode. Segera hadir!</p>
                <?php else: ?>
                    <?php foreach ($episodes as $eps): ?>
                    <a href="episode.php?id=<?= $eps['id'] ?>&slug=<?= $slug ?>" class="episode-card">
                        <i class="fas fa-play"></i> Episode <?= $eps['eps'] ?>
                    </a>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <footer>
        <div class="container">
            <p>&copy; <?= date('Y') ?> ZerozNime - All Rights Reserved</p>
        </div>
    </footer>
</body>
</html>