<?php
// functions.php - Semua fungsi database lengkap

require_once 'config.php';

// ========== ANIME ==========
function getAllAnime($limit = null, $offset = 0) {
    global $conn;
    $sql = "SELECT a.*, t.title as type_name 
            FROM anime_list a 
            LEFT JOIN anime_type t ON a.type = t.id 
            ORDER BY a.created_at DESC";
    if ($limit) $sql .= " LIMIT $offset, $limit";
    return $conn->query($sql)->fetch_all(MYSQLI_ASSOC);
}

function getAnimeBySlug($slug) {
    global $conn;
    $slug = $conn->real_escape_string($slug);
    $result = $conn->query("SELECT a.*, t.title as type_name 
                            FROM anime_list a 
                            LEFT JOIN anime_type t ON a.type = t.id 
                            WHERE a.seo_slug = '$slug'");
    return $result->fetch_assoc();
}

function getAnimeById($id) {
    global $conn;
    $result = $conn->query("SELECT * FROM anime_list WHERE id = $id");
    return $result->fetch_assoc();
}

function getLatestAnime($limit = 12) {
    global $conn;
    $result = $conn->query("SELECT * FROM anime_list ORDER BY created_at DESC LIMIT $limit");
    return $result->fetch_all(MYSQLI_ASSOC);
}

function getAnimeByStatus($status, $limit = null) {
    global $conn;
    $status = $conn->real_escape_string($status);
    $sql = "SELECT * FROM anime_list WHERE status = '$status' ORDER BY created_at DESC";
    if ($limit) $sql .= " LIMIT $limit";
    return $conn->query($sql)->fetch_all(MYSQLI_ASSOC);
}

function getAnimeByType($type_id, $limit = null) {
    global $conn;
    $sql = "SELECT * FROM anime_list WHERE type = $type_id ORDER BY created_at DESC";
    if ($limit) $sql .= " LIMIT $limit";
    return $conn->query($sql)->fetch_all(MYSQLI_ASSOC);
}

function searchAnime($keyword) {
    global $conn;
    $keyword = $conn->real_escape_string($keyword);
    $result = $conn->query("SELECT * FROM anime_list WHERE title LIKE '%$keyword%' OR description LIKE '%$keyword%'");
    return $result->fetch_all(MYSQLI_ASSOC);
}

function addAnime($data) {
    global $conn;
    $sql = "INSERT INTO anime_list (title, thumb, description, type, genre, status, trailer, uploader, seo_slug, seo_description, seo_keywords, created_at) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssisssisss", $data['title'], $data['thumb'], $data['description'], 
                      $data['type'], $data['genre'], $data['status'], $data['trailer'], 
                      $data['uploader'], $data['seo_slug'], $data['seo_description'], $data['seo_keywords']);
    return $stmt->execute();
}

function updateAnime($id, $data) {
    global $conn;
    $sql = "UPDATE anime_list SET title=?, thumb=?, description=?, type=?, genre=?, 
            status=?, trailer=?, seo_slug=?, seo_description=?, seo_keywords=?, updated_at=NOW() 
            WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssissssssi", $data['title'], $data['thumb'], $data['description'], 
                      $data['type'], $data['genre'], $data['status'], $data['trailer'], 
                      $data['seo_slug'], $data['seo_description'], $data['seo_keywords'], $id);
    return $stmt->execute();
}

function deleteAnime($id) {
    global $conn;
    $conn->query("DELETE FROM anime_video WHERE id_anime = $id");
    return $conn->query("DELETE FROM anime_list WHERE id = $id");
}

// ========== EPISODE ==========
function getEpisodes($anime_id) {
    global $conn;
    $result = $conn->query("SELECT * FROM anime_video WHERE id_anime = $anime_id ORDER BY eps ASC");
    return $result->fetch_all(MYSQLI_ASSOC);
}

function getEpisodeById($id) {
    global $conn;
    $result = $conn->query("SELECT * FROM anime_video WHERE id = $id");
    return $result->fetch_assoc();
}

function getEpisodeByAnimeAndEps($anime_id, $eps) {
    global $conn;
    $result = $conn->query("SELECT * FROM anime_video WHERE id_anime = $anime_id AND eps = $eps");
    return $result->fetch_assoc();
}

function addEpisode($data) {
    global $conn;
    $sql = "INSERT INTO anime_video (id_anime, type, eps, video, download, created_at) VALUES (?, ?, ?, ?, ?, NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isiss", $data['id_anime'], $data['type'], $data['eps'], $data['video'], $data['download']);
    return $stmt->execute();
}

function updateEpisode($id, $data) {
    global $conn;
    $sql = "UPDATE anime_video SET type=?, eps=?, video=?, download=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sissi", $data['type'], $data['eps'], $data['video'], $data['download'], $id);
    return $stmt->execute();
}

function deleteEpisode($id) {
    global $conn;
    return $conn->query("DELETE FROM anime_video WHERE id = $id");
}

function countEpisodes($anime_id) {
    global $conn;
    $result = $conn->query("SELECT COUNT(*) as total FROM anime_video WHERE id_anime = $anime_id");
    return $result->fetch_assoc()['total'];
}

// ========== GENRE ==========
function getAllGenres() {
    global $conn;
    return $conn->query("SELECT * FROM anime_genre ORDER BY title")->fetch_all(MYSQLI_ASSOC);
}

function getGenreBySlug($slug) {
    global $conn;
    $slug = $conn->real_escape_string($slug);
    $result = $conn->query("SELECT * FROM anime_genre WHERE seo_slug = '$slug'");
    return $result->fetch_assoc();
}

function getAnimeByGenre($genre_slug) {
    global $conn;
    // Cari ID genre dulu
    $genre = getGenreBySlug($genre_slug);
    if (!$genre) return [];
    
    // Cari anime yang genrenya mengandung keyword (karena genre disimpan sebagai text)
    $result = $conn->query("SELECT * FROM anime_list WHERE genre LIKE '%{$genre['title']}%' OR genre LIKE '%{$genre_slug}%'");
    return $result->fetch_all(MYSQLI_ASSOC);
}

function addGenre($title, $seo_slug) {
    global $conn;
    $title = $conn->real_escape_string($title);
    $seo_slug = $conn->real_escape_string($seo_slug);
    return $conn->query("INSERT INTO anime_genre (title, seo_slug, created_at) VALUES ('$title', '$seo_slug', NOW())");
}

function deleteGenre($id) {
    global $conn;
    return $conn->query("DELETE FROM anime_genre WHERE id = $id");
}

// ========== TYPE ==========
function getAllTypes() {
    global $conn;
    return $conn->query("SELECT * FROM anime_type ORDER BY title")->fetch_all(MYSQLI_ASSOC);
}

// ========== REQUEST ==========
function addRequest($email, $pesan) {
    global $conn;
    $email = $conn->real_escape_string($email);
    $pesan = $conn->real_escape_string($pesan);
    return $conn->query("INSERT INTO request (email, pesan, status, created_at) VALUES ('$email', '$pesan', 0, NOW())");
}

function getAllRequests() {
    global $conn;
    return $conn->query("SELECT * FROM request ORDER BY created_at DESC")->fetch_all(MYSQLI_ASSOC);
}

function updateRequestStatus($id, $status) {
    global $conn;
    return $conn->query("UPDATE request SET status = $status WHERE id = $id");
}

function deleteRequest($id) {
    global $conn;
    return $conn->query("DELETE FROM request WHERE id = $id");
}

// ========== MENU ==========
function getAllMenus() {
    global $conn;
    $direct = $conn->query("SELECT * FROM site_menus WHERE type = 'direct' AND status = 1 ORDER BY id");
    $dropdowns = $conn->query("SELECT * FROM site_menus WHERE type = 'dropdown' AND status = 1 ORDER BY id");
    
    $menus = [];
    foreach ($direct->fetch_all(MYSQLI_ASSOC) as $menu) {
        $menus[] = $menu;
    }
    foreach ($dropdowns->fetch_all(MYSQLI_ASSOC) as $dropdown) {
        $submenus = $conn->query("SELECT * FROM site_menus WHERE type = 'submenu' AND sub_id = {$dropdown['id']} AND status = 1 ORDER BY id");
        $dropdown['submenus'] = $submenus->fetch_all(MYSQLI_ASSOC);
        $menus[] = $dropdown;
    }
    return $menus;
}

function addMenu($title, $type, $link, $sub_id = 0) {
    global $conn;
    $title = $conn->real_escape_string($title);
    $link = $conn->real_escape_string($link);
    return $conn->query("INSERT INTO site_menus (title, type, link, sub_id, status) VALUES ('$title', '$type', '$link', $sub_id, 1)");
}

function updateMenu($id, $title, $link, $status) {
    global $conn;
    return $conn->query("UPDATE site_menus SET title = '$title', link = '$link', status = $status WHERE id = $id");
}

function deleteMenu($id) {
    global $conn;
    $conn->query("DELETE FROM site_menus WHERE sub_id = $id");
    return $conn->query("DELETE FROM site_menus WHERE id = $id");
}

// ========== STATISTIK ==========
function addStat($by_page, $by_id, $ip) {
    global $conn;
    $by_page = $conn->real_escape_string($by_page);
    $ip = $conn->real_escape_string($ip);
    return $conn->query("INSERT INTO sk_stats (by_page, by_id, created_at, ip) VALUES ('$by_page', $by_id, NOW(), '$ip')");
}

function getTotalViews() {
    global $conn;
    $result = $conn->query("SELECT COUNT(*) as total FROM sk_stats");
    return $result->fetch_assoc()['total'];
}

function getTodayViews() {
    global $conn;
    $result = $conn->query("SELECT COUNT(*) as total FROM sk_stats WHERE DATE(created_at) = CURDATE()");
    return $result->fetch_assoc()['total'];
}

// ========== USER / ADMIN ==========
function loginAdmin($username, $password) {
    global $conn;
    $username = $conn->real_escape_string($username);
    $result = $conn->query("SELECT * FROM users WHERE email = '$username'");
    $user = $result->fetch_assoc();
    if ($user && password_verify($password, $user['password'])) {
        return $user;
    }
    return false;
}

function getAllUsers() {
    global $conn;
    return $conn->query("SELECT id, name, email, role FROM users")->fetch_all(MYSQLI_ASSOC);
}

// ========== HELPER FUNCTIONS ==========
function uploadFile($file, $target_dir = 'uploads/') {
    $target_file = $target_dir . time() . '_' . basename($file['name']);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $allowed = unserialize(ALLOWED_EXTENSIONS);
    
    if (!in_array($imageFileType, $allowed)) return false;
    if ($file['size'] > MAX_FILE_SIZE) return false;
    
    if (move_uploaded_file($file['tmp_name'], $target_file)) {
        return basename($target_file);
    }
    return false;
}

function createSlug($string) {
    $string = strtolower($string);
    $string = preg_replace('/[^a-z0-9-]/', '-', $string);
    $string = preg_replace('/-+/', '-', $string);
    return trim($string, '-');
}

function formatDate($datetime) {
    return date('d F Y', strtotime($datetime));
}

function truncate($text, $length = 150) {
    if (strlen($text) <= $length) return $text;
    return substr($text, 0, $length) . '...';
}

function isAdmin() {
    return isset($_SESSION['admin']) && $_SESSION['admin']['role'] == 'admin';
}

function isModerator() {
    return isset($_SESSION['admin']) && in_array($_SESSION['admin']['role'], ['admin', 'moderator']);
}
?>