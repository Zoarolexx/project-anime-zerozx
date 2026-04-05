<?php 
session_start();
require_once 'functions.php';

$keyword = $_GET['q'] ?? '';
$results = [];
if ($keyword) {
    $results = searchAnime($keyword);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pencarian: <?= $keyword ?> - <?= SITE_NAME ?></title>
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
        <div class="search-header">
            <h2><i class="fas fa-search"></i> Hasil pencarian: "<?= htmlspecialchars($keyword) ?>"</h2>
            <p>Ditemukan <?= count($results) ?> anime</p>
        </div>

        <div class="anime-grid">
            <?php if (empty($results)): ?>
                <p class="no-data">Tidak ada anime yang cocok dengan kata kunci "<?= htmlspecialchars($keyword) ?>"</p>
            <?php else: ?>
                <?php foreach ($results as $anime): ?>
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
</body>
</html>