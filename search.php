<?php
// search.php
include_once 'config/database.php';
include_once 'classes/Hall.php';
include_once 'auth/session.php';

$database = new Database();
$db = $database->getConnection();
$hall = new Hall($db);

$search_term = $_GET['search'] ?? '';
$capacity = $_GET['capacity'] ?? '';
$type = $_GET['type'] ?? '';
$date = $_GET['date'] ?? '';

$halls = [];
if ($search_term || $capacity || $type) {
    $halls = $hall->searchHalls($search_term, $capacity, $type);
} else {
    $halls = $hall->getAllHalls();
}

include_once 'includes/header.php';
?>

<div class="container">
    <div style="margin: 2rem 0;">
        <h1>Search Results</h1>
        <?php if ($search_term): ?>
            <p>Results for "<?= htmlspecialchars($search_term) ?>"</p>
        <?php endif; ?>
        <?php if ($capacity): ?>
            <p>Minimum capacity: <?= htmlspecialchars($capacity) ?> people</p>
        <?php endif; ?>
    </div>
    
    <div class="search-box">
        <form method="GET" class="search-form">
            <div class="form-group">
                <label for="search">Search</label>
                <input type="text" id="search" name="search" placeholder="Hall name, location..." value="<?= htmlspecialchars($search_term) ?>">
            </div>
            <div class="form-group">
                <label for="date">Date</label>
                <input type="date" id="date" name="date" min="<?= date('Y-m-d') ?>" value="<?= htmlspecialchars($date) ?>">
            </div>
            <div class="form-group">
                <label for="capacity">Capacity</label>
                <select id="capacity" name="capacity">
                    <option value="">Any Size</option>
                    <option value="50" <?= $capacity == '50' ? 'selected' : '' ?>>50+ People</option>
                    <option value="100" <?= $capacity == '100' ? 'selected' : '' ?>>100+ People</option>
                    <option value="200" <?= $capacity == '200' ? 'selected' : '' ?>>200+ People</option>
                    <option value="500" <?= $capacity == '500' ? 'selected' : '' ?>>500+ People</option>
                </select>
            </div>
            <div class="form-group">
                <label for="type">Hall Type</label>
                <select id="type" name="type">
                    <option value="">Any Type</option>
                    <option value="Lecture Hall" <?= ($type ?? '') == 'Lecture Hall' ? 'selected' : '' ?>>Lecture Hall</option>
                    <option value="Exam Hall" <?= ($type ?? '') == 'Exam Hall' ? 'selected' : '' ?>>Exam Hall</option>
                    <option value="Conference Hall" <?= ($type ?? '') == 'Conference Hall' ? 'selected' : '' ?>>Conference Hall</option>
                    <option value="Auditorium" <?= ($type ?? '') == 'Auditorium' ? 'selected' : '' ?>>Auditorium</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary btn-search">Search</button>
        </form>
    </div>
    
    <?php if (empty($halls)): ?>
        <div style="text-align: center; padding: 3rem;">
            <h3>No halls found</h3>
            <p>Try adjusting your search criteria or <a href="halls.php" style="color: var(--purple);">browse all halls</a>.</p>
        </div>
    <?php else: ?>
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
                        <div class="hall-price">LKR <?= number_format($hall_item['price_per_hour'], 2) ?>/hour</div>
                        <p style="color: #666; margin-bottom: 1rem;"><?= htmlspecialchars(substr($hall_item['description'], 0, 100)) ?>...</p>
                        <a href="book_hall.php?id=<?= $hall_item['id'] ?>" class="btn btn-primary" style="width: 100%;">Book Now</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php include_once 'includes/footer.php'; ?>