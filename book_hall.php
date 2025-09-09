<?php
// book_hall.php 

include_once 'config/database.php';
include_once 'classes/Hall.php';
include_once 'classes/Booking.php';
include_once 'auth/session.php';

requireLogin();

$conn = getConnection();

// Validate hall id
if (!isset($_GET['id'])) {
    header('Location: halls.php');
    exit();
}

$hall_data = getHallById($conn, $_GET['id']);
if (!$hall_data) {
    header('Location: halls.php');
    exit();
}

$message = '';
$error = '';

// Handle booking submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $hall_id = $hall_data['id'];
    $booking_date = $_POST['booking_date'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];
    $purpose = $_POST['purpose'];
    $status = 'pending';

    // Calculate total amount
    $start = new DateTime($start_time);
    $end = new DateTime($end_time);
    $interval = $end->diff($start);
    $hours = $interval->h + ($interval->i / 60);

    $total_amount = $hours * $hall_data['price_per_hour'];

    if (createBooking($conn, $user_id, $hall_id, $booking_date, $start_time, $end_time, $purpose, $total_amount, $status)) {
        $message = 'Booking request submitted successfully! Your booking is pending approval.';
    } else {
        $error = 'Booking failed. The hall might be unavailable for the selected time.';
    }
}

include_once 'includes/header.php';
?>

<div class="container">
    <div style="margin: 2rem 0;">
        <a href="halls.php" style="color: var(--black); text-decoration: none;">← Back to Halls</a>
        <h1><br><?= htmlspecialchars($hall_data['name']) ?></h1>
    </div>
    
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 3rem; margin: 2rem 0;">
        <div>
            <div class="hall-image" style="height: 300px; border-radius: 15px; margin-bottom: 2rem;">
                <?php if ($hall_data['image_url']): ?>
                    <img src="<?= htmlspecialchars($hall_data['image_url']) ?>" alt="<?= htmlspecialchars($hall_data['name']) ?>" style="width: 100%; height: 100%; object-fit: cover; border-radius: 15px;">
                <?php else: ?>
                    🏛️ <?= htmlspecialchars($hall_data['name']) ?>
                <?php endif; ?>
            </div>
            
            <div style="background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.1);">
                <h3>Hall Details</h3>
                <p><strong>Location:</strong> <?= htmlspecialchars($hall_data['location']) ?></p>
                <p><strong>Capacity:</strong> <?= $hall_data['capacity'] ?> people</p>
                <p><strong>Type:</strong> <?= htmlspecialchars($hall_data['type']) ?></p>
                <p><strong>Price:</strong> LKR<?= number_format($hall_data['price_per_hour'], 2) ?>/hour</p>
                <p><strong>Amenities:</strong> <?= htmlspecialchars($hall_data['amenities']) ?></p>
                <p><strong>Description:</strong> <?= htmlspecialchars($hall_data['description']) ?></p>
            </div>
        </div>
        
        <div>
            <div style="background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.1);">
                <h3>Book This Hall</h3>
                
                <?php if ($message): ?>
                    <div class="alert alert-success"><?= htmlspecialchars($message) ?></div>
                <?php endif; ?>
                
                <?php if ($error): ?>
                    <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>
                
                <form method="POST">
                    <div class="form-group">
                        <label for="booking_date">Date</label>
                        <input type="date" id="booking_date" name="booking_date" required min="<?= date('Y-m-d') ?>">
                    </div>
                    
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                        <div class="form-group">
                            <label for="start_time">Start Time</label>
                            <input type="time" id="start_time" name="start_time" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="end_time">End Time</label>
                            <input type="time" id="end_time" name="end_time" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="purpose">Purpose of Booking</label>
                        <textarea id="purpose" name="purpose" rows="3" style="padding: 0.75rem; border: 2px solid #e1e5e9; border-radius: 10px; font-size: 1rem; width: 100%; resize: vertical;" placeholder="Describe the event or purpose..."></textarea>
                    </div>
                    
                    <div style="background: var(--light-gray); padding: 1rem; border-radius: 10px; margin: 1rem 0;">
                        <p><strong>Estimated Cost:</strong> <span id="total-cost">LKR 0.00</span></p>
                        <p style="font-size: 0.9rem; color: #666;">Cost will be calculated based on duration</p><br>
                        <p style="font-size: 0.9rem; color: #cd9999ff;">Please note: Payment must be made onsite. You cannot cancel a booking within two days of the booking date.</p>
                    </div>
                    
                    <button type="submit" class="btn btn-primary" style="width: 100%;">Submit Booking Request</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function calculateCost() {
    const startTime = document.getElementById('start_time').value;
    const endTime = document.getElementById('end_time').value;
    const pricePerHour = <?= $hall_data['price_per_hour'] ?>;
    
    if (startTime && endTime) {
        const start = new Date('1970-01-01T' + startTime + ':00');
        const end = new Date('1970-01-01T' + endTime + ':00');
        
        if (end > start) {
            const hours = (end - start) / (1000 * 60 * 60);
            const totalCost = hours * pricePerHour;
            document.getElementById('total-cost').textContent = 'LKR ' + totalCost.toFixed(2);
        } else {
            document.getElementById('total-cost').textContent = 'Invalid time range';
        }
    }
}

document.getElementById('start_time').addEventListener('change', calculateCost);
document.getElementById('end_time').addEventListener('change', calculateCost);
</script>

<?php include_once 'includes/footer.php'; ?>