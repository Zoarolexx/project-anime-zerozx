<?php 
session_start();
require_once '../functions.php';

if (!isAdmin()) {
    header('Location: login.php');
    exit;
}

$total_anime = count(getAllAnime());
$total_episodes = 0;
$anime_list = getAllAnime();
foreach ($anime_list as $a) {
    $total_episodes += countEpisodes($a['id']);
}
$total_requests = count(getAllRequests());
$total_views = getTotalViews();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard - <?= SITE_NAME ?></title>
    <link rel="stylesheet" href="../style.css">
    <style>
        .admin-container { display: flex; }
        .admin-sidebar { width: 250px; background: #1a1a2e; min-height: 100vh; padding: 20px; }
        .admin-sidebar a { display: block; color: #fff; padding: 10px; margin: 5px 0; text-decoration: none; border-radius: 8px; }
        .admin-sidebar a:hover { background: #e94560; }
        .admin-content { flex: 1; padding: 20px; }
        .stats-dashboard { display: grid; grid-template-columns: repeat(4,1fr); gap: 20px; margin-bottom: 30px; }
        .stat-box { background: #fff; padding: 20px; border-radius: 12px; text-align: center; }
        .dark-mode .stat-box { background: #1a1a2e; }
    </style>
</head>
<body class="dark-mode">
<div class="admin-container">
    <div class="admin-sidebar">
        <h3 style="color:#e94560">ZerozNime Admin</h3>
        <a href="index.php">📊 Dashboard</a>
        <a href="anime_add.php">➕ Tambah Anime</a>
        <a href="genre_manage.php">🏷️ Kelola Genre</a>
        <a href="request_manage.php">📨 Request</a>
        <a href="menu_manage.php">📋 Menu</a>
        <a href="../index.php">🌐 Lihat Website</a>
        <a href="logout.php">🚪 Logout</a>
    </div>
    <div class="admin-content">
        <h2>Dashboard</h2>
        <div class="stats-dashboard">
            <div class="stat-box"><h3><?= $total_anime ?></h3><p>Total Anime</p></div>
            <div class="stat-box"><h3><?= $total_episodes ?></h3><p>Total Episode</p></div>
            <div class="stat-box"><h3><?= $total_requests ?></h3><p>Request Masuk</p></div>
            <div class="stat-box"><h3><?= $total_views ?></h3><p>Total Views</p></div>
        </div>
        
        <h3>Daftar Anime</h3>
        <table>
            <tr><th>ID</th><th>Judul</th><th>Status</th><th>Episode</th><th>Aksi</th></tr>
            <?php foreach ($anime_list as $a): ?>
            <tr>
                <td><?= $a['id'] ?></td>
                <td><?= $a['title'] ?></td>
                <td><?= $a['status'] ?></td>
                <td><?= countEpisodes($a['id']) ?></td>
                <td>
                    <a href="anime_edit.php?id=<?= $a['id'] ?>">✏️ Edit</a>
                    <a href="?delete=<?= $a['id'] ?>" onclick="return confirm('Hapus?')">🗑️ Hapus</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
</div>
</body>
</html>