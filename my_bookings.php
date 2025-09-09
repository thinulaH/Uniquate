<?php
// my_bookings.php
include_once 'config/database.php';
include_once 'classes/Booking.php';
include_once 'auth/session.php';

requireLogin();

// Get database connection using classes function
$db = getConnection();

// Get current user's bookings
$user_id = $_SESSION['user_id'];
$my_bookings = getUserBookings($db, $user_id);

$message = '';
$error = '';

// Handle booking cancellation
if (isset($_GET['cancel']) && isset($_GET['id'])) {
    $booking_id = $_GET['id'];
    
    // Verify the booking belongs to the current user
    $booking_found = false;
    foreach ($my_bookings as $booking) {
        if ($booking['id'] == $booking_id) {
            $booking_found = true;
            break;
        }
    }
    
    if ($booking_found) {
        if (canModifyBooking($db, $booking_id)) {
            if (cancelBooking($db, $booking_id)) {
                $message = "Booking cancelled successfully.";
                // Refresh bookings list
                $my_bookings = getUserBookings($db, $user_id);
            } else {
                $error = "Failed to cancel booking.";
            }
        } else {
            $error = "Cannot cancel booking. Bookings can only be cancelled more than 2 days before the event.";
        }
    } else {
        $error = "Booking not found or access denied.";
    }
}

include_once 'includes/header.php';
?>

<div class="container">
    <div style="margin: 2rem 0;">
        <h1>My Bookings</h1>
        <p>View and manage your hall bookings</p>
    </div>

    <?php if ($message): ?>
        <div class="alert alert-success"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <?php if ($error): ?>
        <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <?php if (empty($my_bookings)): ?>
        <div style="text-align: center; padding: 3rem; background: white; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.1);">
            <h3>No bookings found</h3>
            <p>You haven't made any bookings yet.</p>
            <a href="halls.php" class="btn btn-primary">Browse Halls</a>
        </div>
    <?php else: ?>
        <div style="display: grid; gap: 1.5rem;">
            <?php foreach ($my_bookings as $booking): ?>
                <div style="background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.1); border-left: 5px solid var(--purple);">
                    <div style="display: grid; grid-template-columns: 1fr auto; gap: 2rem; align-items: start;">
                        <div>
                            <h3 style="margin: 0 0 0.5rem 0; color: var(--purple);">
                                <?= htmlspecialchars($booking['hall_name']) ?>
                            </h3>
                            <p style="margin: 0 0 1rem 0; color: #666;">
                                📍 <?= htmlspecialchars($booking['location']) ?>
                            </p>
                            
                            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin: 1rem 0;">
                                <div>
                                    <strong>Date:</strong><br>
                                    <?= date('l, F j, Y', strtotime($booking['booking_date'])) ?>
                                </div>
                                <div>
                                    <strong>Time:</strong><br>
                                    <?= date('g:i A', strtotime($booking['start_time'])) ?> - <?= date('g:i A', strtotime($booking['end_time'])) ?>
                                </div>
                                <div>
                                    <strong>Purpose:</strong><br>
                                    <?= htmlspecialchars($booking['purpose']) ?>
                                </div>
                                <div>
                                    <strong>Total Amount:</strong><br>
                                    LKR <?= number_format($booking['total_amount'], 2) ?>
                                </div>
                            </div>
                            
                            <div>
                                <strong>Booked on:</strong> <?= date('M j, Y \a\t g:i A', strtotime($booking['created_at'])) ?>
                            </div>
                        </div>
                        
                        <div style="text-align: right;">
                            <div style="margin-bottom: 1rem;">
                                <span class="status-badge status-<?= $booking['status'] ?>">
                                    <?= ucfirst($booking['status']) ?>
                                </span>
                            </div>
                            
                            <?php if ($booking['status'] === 'pending' || $booking['status'] === 'confirmed'): ?>
                                <?php 
                                $can_cancel = canModifyBooking($db, $booking['id']);
                                $booking_date = new DateTime($booking['booking_date']);
                                $today = new DateTime();
                                $days_until = $today->diff($booking_date)->days;
                                ?>
                                
                                <?php if ($can_cancel): ?>
                                    <a href="my_bookings.php?cancel=1&id=<?= $booking['id'] ?>" 
                                       class="btn btn-danger" 
                                       onclick="return confirm('Are you sure you want to cancel this booking?')"
                                       style="font-size: 0.9rem;">
                                        Cancel Booking
                                    </a>
                                <?php else: ?>
                                    <small style="color: #666; display: block; margin-top: 0.5rem;">
                                        Cannot cancel<br>
                                        (Less than 2 days remaining)
                                    </small>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <div style="text-align: center; margin: 3rem 0;">
            <a href="halls.php" class="btn btn-secondary">Book Another Hall</a>
        </div>
    <?php endif; ?>
</div>
<div><br><br><br><br><br><br><br><br><br><br><br><br><div>

<?php include_once 'includes/footer.php'; ?>