
<?php
// my_bookings.php
include_once 'config/database.php';
include_once 'classes/Booking.php';
include_once 'auth/session.php';

requireLogin();

$database = new Database();
$db = $database->getConnection();
$booking = new Booking($db);

$user_bookings = $booking->getUserBookings($_SESSION['user_id']);

include_once 'includes/header.php';
?>

<style>
.page-wrapper {
    min-height: 65vh;
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
                            </tr>
                            <tr>
                                <td colspan="7">
                                    <div class="booking-actions">
                                        <a href="edit_booking.php?id=<?= $booking_item['id'] ?>" class="btn">Edit</a>
                                        <a href="cancel_booking.php?id=<?= $booking_item['id'] ?>" class="btn btn-danger">Cancel</a>
                                    </div>
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
