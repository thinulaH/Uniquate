<?php
// index.php (Home Page)
include_once 'config/database.php';
include_once 'classes/Hall.php';
include_once 'auth/session.php';

$database = new Database();
$db = $database->getConnection();
$hall = new Hall($db);

// Get featured halls (limit to 6)
$featured_halls = $hall->getAllHalls();
$featured_halls = array_slice($featured_halls, 0, 6);

// Redirect admins before output starts
if (isLoggedIn() && isAdmin()) {
    header("Location: admin.php");
    exit();
}

include_once 'includes/header.php';
?>

<style>
/* body {
    background: linear-gradient(135deg, #fffcd7ff 0%, #8e7295ff 100%);
    min-height: 100vh;
    margin: 0;
    font-family: 'Segoe UI', Arial, sans-serif;
} */

.hero {
    margin-bottom: 0;
}
</style>
<div class="hero" >
    <div class="container">
        <h1>Find the Perfect Hall for Your Event</h1>
        <p>Book university halls and rooms for conferences, meetings, celebrations, and academic events with ease</p>

    <div class="search-box">
        <form action="search.php" method="GET" class="search-form">
            <div class="form-group">
                <!-- <label for="search">Hall Name or Location</label> -->
                <input type="text" id="search" name="search" placeholder="Search halls...">
            </div>
            <div class="form-group">
                <!-- <label for="date">Date</label> -->
                <input type="date" id="date" name="date" min="<?= date('Y-m-d') ?>">
            </div>
            <div class="form-group" style="width:200px; height:100%;">
                <!-- <label for="capacity">Minimum Capacity</label> -->
                <select id="capacity" name="capacity">
                    <option value="">Any Size</option>
                    <option value="50">50+ People</option>
                    <option value="100">100+ People</option>
                    <option value="200">200+ People</option>
                    <option value="500">500+ People</option>
                </select>
            </div>
            <div class="form-group" style="width:200px; height:100%;">
                <!-- <label for="type">Hall Type</label> -->
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
    <div class="slide" ><img src="images/hall1.jpeg" alt="Hall 1"></div>
    <div class="slide"><img src="images/hall2.jpg" alt="Hall 2"></div>
    <div class="slide"><img src="images/hall3.jpg" alt="Hall 3"></div>
    <div class="slide"><img src="images/hall4.jpg" alt="Hall 4"></div>
</div>

<style>
.photo-slider {
    width: 100%;
    max-width: 1000px;
    margin: 0 auto 2rem;
    display: flex;
    gap: 1rem;
    overflow: hidden;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
}
.photo-slider .slide {
    flex: 1;
    overflow: hidden;
    border-radius: 10px;
}
.photo-slider img {
    width: 100%;
    height: 250px;
    object-fit: cover;
    object-position: center;
    display: block;
    border-radius: 10px;
    transition: transform 0.4s ease;
}
.photo-slider img:hover {
    transform: scale(1.3);
    object-fit: cover;
}
</style>

<!-- <div class="container">
    <h2 style="text-align: center; margin: 3rem 0 2rem;">Featured Halls</h2>
    
    <div class="halls-grid">
        <?php foreach ($featured_halls as $hall_item): ?>
            <div class="hall-card">
                <div class="hall-image">
                    <?php if (!empty($hall_item['image_url'])): ?>
                        <img src="<?= htmlspecialchars($hall_item['image_url']) ?>" alt="<?= htmlspecialchars($hall_item['name']) ?>" style="width: 100%; height: 100%; object-fit: cover;">
                    <?php else: ?>
                        🏛️ <?= htmlspecialchars($hall_item['name']) ?>
                    <?php endif; ?>
                </div>
                <div class="hall-info">
                    <div class="hall-name"><?= htmlspecialchars($hall_item['name']) ?></div>
                    <div class="hall-location">📍 <?= htmlspecialchars($hall_item['location']) ?></div>
                    <div class="hall-details">
                        <span>👥 <?= $hall_item['capacity'] ?> people</span>
                        <span>🏷️ <?= htmlspecialchars($hall_item['amenities']) ?></span>
                    </div>
                    <div class="hall-price">$<?= number_format($hall_item['price_per_hour'], 2) ?>/hour</div>
                    <p style="color: #666; margin-bottom: 1rem;"><?= htmlspecialchars(substr($hall_item['description'], 0, 100)) ?>...</p>
                    <a href="book_hall.php?id=<?= $hall_item['id'] ?>" class="btn btn-primary" style="width: 100%;">Book Now</a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    
    <div style="text-align: center; margin: 3rem 0;">
        <a href="halls.php" class="btn btn-secondary">View All Halls</a>
    </div>
</div> -->


<?php include_once 'includes/footer.php'; ?>