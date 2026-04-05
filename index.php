<?php 
session_start();
require_once 'functions.php';
addStat('page_master', 0, $_SERVER['REMOTE_ADDR']);

$latest_anime = getLatestAnime(12);
$ongoing_anime = getAnimeByStatus('sedang', 6);
$completed_anime = getAnimeByStatus('selesai', 6);
$total_views = getTotalViews();
$today_views = getTodayViews();
$menus = getAllMenus();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= SITE_NAME ?> - <?= SITE_DESCRIPTION ?></title>
    <meta name="description" content="<?= SITE_DESCRIPTION ?>">
    <meta name="keywords" content="<?= SITE_KEYWORDS ?>">
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body class="<?= $GLOBALS['theme_dark'] == 'true' ? 'dark-mode' : '' ?>">
    
    <!-- Navbar -->
    <nav class="navbar">
        <div class="container">
            <div class="logo">
                <a href="index.php">
                    <i class="fas fa-film"></i>
                    <h1>Zeroz<span>Nime</span></h1>
                </a>
            </div>
            
            <ul class="nav-menu">
                <?php foreach ($menus as $menu): ?>
                    <?php if ($menu['type'] == 'direct'): ?>
                        <li><a href="<?= $menu['link'] ?>"><?= $menu['title'] ?></a></li>
                    <?php elseif ($menu['type'] == 'dropdown' && !empty($menu['submenus'])): ?>
                        <li class="dropdown">
                            <a href="<?= $menu['link'] ?>"><?= $menu['title'] ?> <i class="fas fa-chevron-down"></i></a>
                            <ul class="dropdown-menu">
                                <?php foreach ($menu['submenus'] as $sub): ?>
                                    <li><a href="<?= $sub['link'] ?>"><?= $sub['title'] ?></a></li>
                                <?php endforeach; ?>
                            </ul>
                        </li>
                    <?php endif; ?>
                <?php endforeach; ?>
                <li>
                    <button id="theme-toggle" class="theme-btn">
                        <i class="fas fa-moon"></i>
                    </button>
                </li>
            </ul>
            
            <div class="mobile-menu">
                <i class="fas fa-bars"></i>
            </div>
        </div>
    </nav>

    <!-- Hero Slider -->
    <section class="hero-slider">
        <div class="slider-container">
            <?php 
            $slider_anime = array_slice($latest_anime, 0, 5);
            foreach ($slider_anime as $index => $anime): 
            ?>
            <div class="slide <?= $index == 0 ? 'active' : '' ?>" style="background-image: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), url('uploads/<?= $anime['thumb'] ?>')">
                <div class="slide-content">
                    <h2><?= $anime['title'] ?></h2>
                    <p><?= truncate(strip_tags($anime['description']), 120) ?></p>
                    <div class="slide-meta">
                        <span class="badge <?= $anime['status'] ?>"><?= $anime['status'] == 'selesai' ? 'Completed' : ($anime['status'] == 'sedang' ? 'Ongoing' : 'Upcoming') ?></span>
                        <span class="genre"><?= str_replace(',', ' • ', $anime['genre']) ?></span>
                    </div>
                    <a href="anime.php?slug=<?= $anime['seo_slug'] ?>" class="btn-primary">Lihat Detail <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <button class="slider-prev"><i class="fas fa-chevron-left"></i></button>
        <button class="slider-next"><i class="fas fa-chevron-right"></i></button>
        <div class="slider-dots">
            <?php for ($i = 0; $i < count($slider_anime); $i++): ?>
                <span class="dot <?= $i == 0 ? 'active' : '' ?>"></span>
            <?php endfor; ?>
        </div>
    </section>

    <main class="container">
        <!-- Statistik -->
        <div class="stats-grid">
            <div class="stat-card">
                <i class="fas fa-tv"></i>
                <div class="stat-info">
                    <h3><?= count($latest_anime) ?>+</h3>
                    <p>Total Anime</p>
                </div>
            </div>
            <div class="stat-card">
                <i class="fas fa-eye"></i>
                <div class="stat-info">
                    <h3><?= $total_views ?>+</h3>
                    <p>Total Views</p>
                </div>
            </div>
            <div class="stat-card">
                <i class="fas fa-calendar-day"></i>
                <div class="stat-info">
                    <h3><?= $today_views ?>+</h3>
                    <p>Hari Ini</p>
                </div>
            </div>
            <div class="stat-card">
                <i class="fas fa-video"></i>
                <div class="stat-info">
                    <h3>HD</h3>
                    <p>Kualitas</p>
                </div>
            </div>
        </div>

        <!-- Ongoing Anime -->
        <div class="section-header">
            <h3><i class="fas fa-fire"></i> Sedang Tayang</h3>
            <a href="index.php?status=sedang" class="view-all">Lihat Semua →</a>
        </div>
        <div class="anime-grid">
            <?php foreach ($ongoing_anime as $anime): ?>
            <div class="anime-card">
                <a href="anime.php?slug=<?= $anime['seo_slug'] ?>">
                    <div class="card-img">
                        <img src="uploads/<?= $anime['thumb'] ?>" alt="<?= $anime['title'] ?>">
                        <span class="status <?= $anime['status'] ?>"><?= $anime['status'] == 'selesai' ? 'Completed' : ($anime['status'] == 'sedang' ? 'Ongoing' : 'Upcoming') ?></span>
                    </div>
                    <h4><?= $anime['title'] ?></h4>
                    <p class="genre"><?= str_replace(',', ' • ', $anime['genre']) ?></p>
                </a>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Completed Anime -->
        <div class="section-header">
            <h3><i class="fas fa-check-circle"></i> Selesai Tayang</h3>
            <a href="index.php?status=selesai" class="view-all">Lihat Semua →</a>
        </div>
        <div class="anime-grid">
            <?php foreach ($completed_anime as $anime): ?>
            <div class="anime-card">
                <a href="anime.php?slug=<?= $anime['seo_slug'] ?>">
                    <div class="card-img">
                        <img src="uploads/<?= $anime['thumb'] ?>" alt="<?= $anime['title'] ?>">
                        <span class="status <?= $anime['status'] ?>"><?= $anime['status'] == 'selesai' ? 'Completed' : ($anime['status'] == 'sedang' ? 'Ongoing' : 'Upcoming') ?></span>
                    </div>
                    <h4><?= $anime['title'] ?></h4>
                    <p class="genre"><?= str_replace(',', ' • ', $anime['genre']) ?></p>
                </a>
            </div>
            <?php endforeach; ?>
        </div>
    </main>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-logo">
                    <h3>Zeroz<span>Nime</span></h3>
                    <p>Streaming anime subtitle Indonesia gratis tanpa iklan.</p>
                </div>
                <div class="footer-links">
                    <h4>Menu</h4>
                    <a href="index.php">Home</a>
                    <a href="request.php">Request Anime</a>
                    <a href="admin/index.php">Admin</a>
                </div>
                <div class="footer-social">
                    <h4>Follow Us</h4>
                    <div class="social-icons">
                        <a href="<?= $social['facebook'] ?>"><i class="fab fa-facebook"></i></a>
                        <a href="<?= $social['instagram'] ?>"><i class="fab fa-instagram"></i></a>
                        <a href="<?= $social['twitter'] ?>"><i class="fab fa-twitter"></i></a>
                        <a href="<?= $social['youtube'] ?>"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; <?= date('Y') ?> ZerozNime - All Rights Reserved</p>
            </div>
        </div>
    </footer>

    <script>
        // Theme Toggle
        const themeToggle = document.getElementById('theme-toggle');
        if (themeToggle) {
            themeToggle.addEventListener('click', () => {
                document.body.classList.toggle('dark-mode');
                const isDark = document.body.classList.contains('dark-mode');
                document.cookie = `theme_dark=${isDark}; path=/`;
            });
        }

        // Slider
        let currentSlide = 0;
        const slides = document.querySelectorAll('.slide');
        const dots = document.querySelectorAll('.dot');
        
        function showSlide(index) {
            slides.forEach(slide => slide.classList.remove('active'));
            dots.forEach(dot => dot.classList.remove('active'));
            slides[index].classList.add('active');
            dots[index].classList.add('active');
        }
        
        document.querySelector('.slider-prev')?.addEventListener('click', () => {
            currentSlide = (currentSlide - 1 + slides.length) % slides.length;
            showSlide(currentSlide);
        });
        
        document.querySelector('.slider-next')?.addEventListener('click', () => {
            currentSlide = (currentSlide + 1) % slides.length;
            showSlide(currentSlide);
        });
        
        dots.forEach((dot, i) => {
            dot.addEventListener('click', () => {
                currentSlide = i;
                showSlide(currentSlide);
            });
        });
        
        setInterval(() => {
            currentSlide = (currentSlide + 1) % slides.length;
            showSlide(currentSlide);
        }, 5000);
    </script>
</body>
</html>