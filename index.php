<?php
// index.php (Home Page)
include_once 'config/database.php'; 
include_once 'auth/session.php';

// Redirect admins before output starts
if (isLoggedIn() && isAdmin()) {
    header("Location: admin.php");
    exit();
}

$db = getConnection();

try {
    $stmt = $db->prepare("SELECT * FROM halls ORDER BY id ASC LIMIT 6");
    $stmt->execute();
    $featured_halls = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $featured_halls = [];
}

include_once 'includes/header.php';
?>

<div class="hero">
    <div class="container">
        <h1>Find the Perfect Hall for Your Event</h1>
        <p>Book university halls and rooms for conferences, meetings, celebrations, and academic events with ease</p>

        <div class="search-box">
            <form action="search.php" method="GET" class="search-form">
                <div class="form-group">
                    <input type="text" id="search" name="search" placeholder="Search halls...">
                </div>
                <div class="form-group">
                    <input type="date" id="date" name="date" min="<?= date('Y-m-d') ?>">
                </div>
                <div class="form-group" style="width:200px;">
                    <select id="capacity" name="capacity">
                        <option value="">Any Size</option>
                        <option value="50">50+ People</option>
                        <option value="100">100+ People</option>
                        <option value="200">200+ People</option>
                        <option value="500">500+ People</option>
                    </select>
                </div>
                <div class="form-group" style="width:200px;">
                    <select id="type" name="type">
                        <option value="">Type</option>
                        <option value="Lecture Hall">Lecture Hall</option>
                        <option value="Exam Hall">Exam Hall</option>
                        <option value="Conference Hall">Conference Hall</option>
                        <option value="Auditorium">Auditorium</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary btn-search">Search</button>
            </form>
        </div>
    </div>
</div>

<div class="photo-slider">
    <div class="slide"><img src="images/hall1.jpeg" alt="Hall 1"></div>
    <div class="slide"><img src="images/hall2.jpg" alt="Hall 2"></div>
    <div class="slide"><img src="images/hall3.jpg" alt="Hall 3"></div>
    <div class="slide"><img src="images/hall4.jpg" alt="Hall 4"></div>
</div>



<?php include_once 'includes/footer.php'; ?>