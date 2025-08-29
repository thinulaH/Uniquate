
<?php
// halls.php
include_once 'config/database.php';
include_once 'classes/Hall.php';
include_once 'auth/session.php';

$database = new Database();
$db = $database->getConnection();
$hall = new Hall($db);

$halls = $hall->getAllHalls();

include_once 'includes/header.php';
?>

<div class="container">
    <div style="margin: 2rem 0;">
        <h1>Browse All Halls</h1>
        <p>Discover the perfect venue for your event from our collection of university halls and rooms.</p>
    </div>
    
    <div class="search-box">
        <form action="search.php" method="GET" class="search-form">
            <div class="form-group">
                <label for="search">Search</label>
                <input type="text" id="search" name="search" placeholder="Hall name, location...">
            </div>
            <div class="form-group">
                <label for="date">Date</label>
                <input type="date" id="date" name="date" min="<?= date('Y-m-d') ?>">
            </div>
            <div class="form-group">
                <label for="capacity">Capacity</label>
                <select id="capacity" name="capacity">
                    <option value="">Any Size</option>
                    <option value="50">50+ People</option>
                    <option value="100">100+ People</option>
                    <option value="200">200+ People</option>
                    <option value="500">500+ People</option>
                </select>
            </div>
            <div class="form-group" style="width:200px; height:100%;">
                <label for="type">Hall Type</label>
                <select id="type" name="type">
                    <option value="">Any Type</option>
                    <option value="Lecture Hall">Lecture Hall</option>
                    <option value="Exam Hall">Exam Hall</option>
                    <option value="Conference Hall">Conference Hall</option>
                    <option value="Auditorium">Auditorium</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Search</button>
        </form>
    </div>
    
    <div class="halls-grid">
        <?php foreach ($halls as $hall_item): ?>
            <div class="hall-card">
                <div class="hall-image">
                    <?php if ($hall_item['image_url']): ?>
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
                        <span>🏫 <?= htmlspecialchars($hall_item['type'] ?: 'General') ?></span>
                        <span>🏷️ <?= htmlspecialchars($hall_item['amenities'] ?: 'Basic amenities') ?></span>
                    </div>
                    <div class="hall-price">$<?= number_format($hall_item['price_per_hour'], 2) ?>/hour</div>
                    <p style="color: #666; margin-bottom: 1rem;"><?= htmlspecialchars(substr($hall_item['description'], 0, 100)) ?>...</p>
                    <a href="book_hall.php?id=<?= $hall_item['id'] ?>" class="btn btn-primary" style="width: 100%;">Book Now</a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php include_once 'includes/footer.php'; ?>
