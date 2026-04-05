<?php 
session_start();
require_once 'functions.php';

$id = $_GET['id'] ?? 0;
$slug = $_GET['slug'] ?? '';
$episode = getEpisodeById($id);
if (!$episode) die("<h1>404 - Episode tidak ditemukan</h1>");

$anime = getAnimeById($episode['id_anime']);
if (!$anime) die("<h1>404 - Anime tidak ditemukan</h1>");

$prev_ep = getEpisodeByAnimeAndEps($anime['id'], $episode['eps'] - 1);
$next_ep = getEpisodeByAnimeAndEps($anime['id'], $episode['eps'] + 1);

addStat('anime_video', $id, $_SERVER['REMOTE_ADDR']);

// Parse download links (bisa JSON atau teks biasa)
$download_links = [];
if ($episode['download']) {
    if (strpos($episode['download'], '{') !== false) {
        $download_links = json_decode($episode['download'], true);
    } else {
        // Format lama: dipisah {{==}} 
        $download_links = explode('{{==}}', $episode['download']);
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nonton <?= $anime['title'] ?> Episode <?= $episode['eps'] ?> - <?= SITE_NAME ?></title>
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
            <div class="nav-actions">
                <a href="anime.php?slug=<?= $slug ?>" class="back-btn"><i class="fas fa-arrow-left"></i> Kembali</a>
            </div>
        </div>
    </nav>

    <main class="container">
        <div class="player-section">
            <div class="video-container">
                <?php 
                $video_url = $episode['video'];
                // Deteksi tipe URL untuk embed yang benar
                if (strpos($video_url, 'youtube.com') !== false || strpos($video_url, 'youtu.be') !== false) {
                    // YouTube embed
                    preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([^&?]+)/', $video_url, $matches);
                    $vid = $matches[1] ?? '';
                    echo '<iframe src="https://www.youtube.com/embed/' . $vid . '" frameborder="0" allowfullscreen></iframe>';
                } elseif (strpos($video_url, 'gdriveplayer') !== false || strpos($video_url, 'drive.google') !== false) {
                    // Google Drive embed
                    echo '<iframe src="' . $video_url . '" frameborder="0" allowfullscreen></iframe>';
                } elseif (strpos($video_url, 'ok.ru') !== false) {
                    // Ok.ru embed
                    echo '<iframe src="' . $video_url . '" frameborder="0" allowfullscreen></iframe>';
                } else {
                    // Direct link atau embed biasa
                    echo '<iframe src="' . $video_url . '" frameborder="0" allowfullscreen></iframe>';
                }
                ?>
            </div>
            
            <div class="player-info">
                <h3><?= $anime['title'] ?> - Episode <?= $episode['eps'] ?></h3>
                
                <div class="episode-nav">
                    <?php if ($prev_ep): ?>
                        <a href="episode.php?id=<?= $prev_ep['id'] ?>&slug=<?= $slug ?>" class="nav-btn">
                            <i class="fas fa-chevron-left"></i> Episode Sebelumnya
                        </a>
                    <?php else: ?>
                        <span class="nav-btn disabled"><i class="fas fa-chevron-left"></i> Episode Sebelumnya</span>
                    <?php endif; ?>
                    
                    <a href="anime.php?slug=<?= $slug ?>" class="nav-btn list-btn">
                        <i class="fas fa-list"></i> Daftar Episode
                    </a>
                    
                    <?php if ($next_ep): ?>
                        <a href="episode.php?id=<?= $next_ep['id'] ?>&slug=<?= $slug ?>" class="nav-btn">
                            Episode Selanjutnya <i class="fas fa-chevron-right"></i>
                        </a>
                    <?php else: ?>
                        <span class="nav-btn disabled">Episode Selanjutnya <i class="fas fa-chevron-right"></i></span>
                    <?php endif; ?>
                </div>
                
                <?php if (!empty($download_links)): ?>
                <div class="download-section">
                    <h4><i class="fas fa-download"></i> Download Episode <?= $episode['eps'] ?></h4>
                    <div class="download-links">
                        <?php foreach ($download_links as $index => $link): ?>
                            <a href="<?= $link ?>" target="_blank" class="download-btn">
                                <i class="fas fa-cloud-download-alt"></i> Server <?= $index + 1 ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
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