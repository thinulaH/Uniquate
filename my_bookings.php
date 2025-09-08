<?php
// my_bookings.php
include_once 'config/database.php';
include_once 'classes/Booking.php';
include_once 'auth/session.php';

requireLogin();

$database = new Database();
$db = $database->getConnection();
$booking = new Booking($db);

// Handle cancellation directly
if (isset($_GET['cancelBooking'])) {
    $booking_id = (int) $_GET['cancelBooking'];

    if ($booking->cancelBooking($booking_id)) {
        $_SESSION['message'] = "Booking cancelled successfully!";
    } else {
        $_SESSION['message'] = "Cannot cancel this booking (less than 2 days left).";
    }

    // Redirect to avoid resubmission on refresh
    header("Location: my_bookings.php");
    exit;
}

$user_bookings = $booking->getUserBookings($_SESSION['user_id']);

include_once 'includes/header.php';
?>

<?php if (isset($_SESSION['message'])): ?>
    <div class="alert" style="padding: 1rem; background-color: #f0f0f0; margin-bottom: 1rem; border-radius: 5px;">
        <?= $_SESSION['message'] ?>
    </div>
    <?php unset($_SESSION['message']); ?>
<?php endif; ?>

<style>
.page-wrapper {
    min-height: 100vh;
    display: flex;
    flex-direction: column;
}
</style>

<div class="page-wrapper">
    <div class="container">
        <div style="margin: 2rem 0;">
            <h1>My Bookings</h1>
            <p>View and manage all your hall bookings</p>
        </div>
        
        <?php if (empty($user_bookings)): ?>
            <div style="text-align: center; padding: 3rem;">
                <h3>No bookings yet</h3>
                <p>You haven't made any bookings yet. <a href="halls.php" style="color: var(--purple);">Browse halls</a> to get started!</p>
            </div>
        <?php else: ?>
            <div class="bookings-table">
                <table>
                    <thead>
                        <tr>
                            <th>Hall Name</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Booked On</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($user_bookings as $booking_item): ?>
                            <tr>
                                <td>
                                    <strong><?= htmlspecialchars($booking_item['hall_name']) ?></strong><br>
                                    <small style="color: #666;">📍 <?= htmlspecialchars($booking_item['location']) ?></small>
                                </td>
                                <td><?= date('M j, Y', strtotime($booking_item['booking_date'])) ?></td>
                                <td><?= date('g:i A', strtotime($booking_item['start_time'])) ?> - <?= date('g:i A', strtotime($booking_item['end_time'])) ?></td>
                                <td>LKR <?= number_format($booking_item['total_amount'], 2) ?></td>
                                <td>
                                    <span class="status-badge status-<?= $booking_item['status'] ?>">
                                        <?= ucfirst($booking_item['status']) ?>
                                    </span>
                                </td>
                                <td><?= date('M j, Y', strtotime($booking_item['created_at'])) ?></td>
                                <td style="padding: 1rem; border-bottom: 1px solid #e1e5e9; text-align: center;">
                                    <?php if($booking_item['status'] !== 'cancelled'): ?>
                                        <a href="my_bookings.php?cancelBooking=<?= $booking_item['id'] ?>" 
                                           onclick="return confirm('Are you sure you want to cancel this booking? (If you want to reactivate this booking you have to contact the admin)')"
                                           style="padding: 0.3rem 0.6rem; background-color: #f44336; color: white; border-radius: 4px; text-decoration: none; font-size: 0.9rem;">Cancel</a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include_once 'includes/footer.php'; ?>