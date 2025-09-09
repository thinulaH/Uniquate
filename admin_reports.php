<?php
// Admin Reports Page (classes version)
include_once 'config/database.php';
include_once 'auth/session.php';
include_once 'classes/User.php';
include_once 'classes/Hall.php';
include_once 'classes/Booking.php';

requireAdmin();

// Get database connection using classes function
$conn = getConnection();

$message = "";
$error = "";

// Fetch data using classes functions
$all_bookings = getAllBookings($conn);
$all_users = getAllUsers($conn);
$all_halls = getAllHalls($conn);

include_once 'includes/header.php';
?>

<div class="admin-sidebar">
    <a href="#">Quick Insights</a> 
    <a href="#users">User Reports</a>
    <a href="#halls">Hall Reports</a>
    <a href="#bookings">Booking Reports</a>
</div>

<div class="container admin-content"> 
    <h1>Admin Reports Page</h1>
    <p>Get insights about the system.</p>
    
    <?php if ($message): ?>
        <div class="alert alert-success"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>
    
    <?php if ($error): ?>
        <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    
    <!-- Quick Stats -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 2rem; margin: 2rem 0;">
        <div style="background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.1); text-align: center;">
            <h1 style="color: var(--purple);"><?= count($all_halls) ?></h1>
            <p>Total Halls</p>
        </div>
        <div style="background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.1); text-align: center;">
            <h1 style="color: var(--tan);"><?= count($all_bookings) ?></h1>
            <p>Total Bookings</p>
        </div>
        <div style="background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.1); text-align: center;">
            <h1 style="color: var(--purple);"><?= count($all_users) ?></h1>
            <p>Registered Users</p>
        </div>
        <div style="background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.1); text-align: center;">
            <h1 style="color: var(--tan);"><?= count(array_filter($all_bookings, function($b) { return $b['status'] === 'pending'; })) ?></h1>
            <p>Pending Requests</p>
        </div>
        <div style="background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.1); text-align: center;">
            <h3 style="color: var(--purple);">
                <?php
                // Sum confirmed bookings in the current month
                $monthly_income = 0.0;
                $currentYm = date('Y-m');
                foreach ($all_bookings as $b) {
                    if (
                        isset($b['booking_date'], $b['status'], $b['total_amount']) &&
                        date('Y-m', strtotime($b['booking_date'])) === $currentYm &&
                        $b['status'] === 'confirmed'
                    ) {
                        $monthly_income += (float)$b['total_amount'];
                    }
                }
                echo 'LKR ' . number_format($monthly_income, 2);
                ?>
            </h3>
            <p>Monthly Income</p>
        </div>
    </div>

    <div class="report-section">
        <div id="users" class="admin-task-container">
            <h3>Users</h3><br>
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; border-radius: 10px; overflow: hidden;">
                    <thead>
                        <tr style="background: var(--light-tan);">
                            <th style="padding: 1rem; text-align: left; border-bottom: 1px solid #e1e5e9;">Username</th>
                            <th style="padding: 1rem; text-align: left; border-bottom: 1px solid #e1e5e9;">Email</th>
                            <th style="padding: 1rem; text-align: left; border-bottom: 1px solid #e1e5e9;">Role</th>
                            <th style="padding: 1rem; text-align: left; border-bottom: 1px solid #e1e5e9;">Registered</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($all_users as $user_item): ?>
                            <tr>
                                <td style="padding: 1rem; border-bottom: 1px solid #e1e5e9;"><?= htmlspecialchars($user_item['username']) ?></td>
                                <td style="padding: 1rem; border-bottom: 1px solid #e1e5e9;"><?= htmlspecialchars($user_item['email']) ?></td>
                                <td style="padding: 1rem; border-bottom: 1px solid #e1e5e9;">
                                    <span class="status-badge <?= $user_item['role'] === 'admin' ? 'status-confirmed' : 'status-pending' ?>">
                                        <?= ucfirst($user_item['role']) ?>
                                    </span>
                                </td>
                                <td style="padding: 1rem; border-bottom: 1px solid #e1e5e9;"><?= date('M j, Y', strtotime($user_item['created_at'])) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <div id="halls" class="admin-task-container" style="background: white; padding:2rem; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.1); margin: 2rem 0;">
            <h3>Halls</h3>
            <br>
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; border-radius: 10px; overflow: hidden;">
                    <thead>
                        <tr style="background: var(--light-tan);">
                            <th style="padding: 1rem; text-align: left; border-bottom: 1px solid #e1e5e9;">Hall Name</th>
                            <th style="padding: 1rem; text-align: left; border-bottom: 1px solid #e1e5e9;">Location</th>
                            <th style="padding: 1rem; text-align: left; border-bottom: 1px solid #e1e5e9;">Capacity</th>
                            <th style="padding: 1rem; text-align: left; border-bottom: 1px solid #e1e5e9;">Type</th>
                            <th style="padding: 1rem; text-align: left; border-bottom: 1px solid #e1e5e9;">Price per Hour</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($all_halls as $hall): ?>
                            <tr>
                                <td style="padding: 1rem; border-bottom: 1px solid #e1e5e9;"><?= htmlspecialchars($hall['name']) ?></td>
                                <td style="padding: 1rem; border-bottom: 1px solid #e1e5e9;"><?= htmlspecialchars($hall['location']) ?></td>
                                <td style="padding: 1rem; border-bottom: 1px solid #e1e5e9;"><?= htmlspecialchars($hall['capacity']) ?></td>
                                <td style="padding: 1rem; border-bottom: 1px solid #e1e5e9;"><?= htmlspecialchars($hall['type']) ?></td>
                                <td style="padding: 1rem; border-bottom: 1px solid #e1e5e9;">LKR <?= number_format($hall['price_per_hour'], 2) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div id="bookings" class="admin-task-container">
        <h3>Bookings</h3><br>
        
        <?php if (empty($all_bookings)): ?>
            <p>No bookings found.</p>
        <?php else: ?>
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; border-radius: 10px; overflow: hidden;">
                    <thead>
                        <tr style="background: var(--light-tan);">
                            <th style="padding: 1rem; text-align: left; border-bottom: 1px solid #e1e5e9;">User</th>
                            <th style="padding: 1rem; text-align: left; border-bottom: 1px solid #e1e5e9;">Hall</th>
                            <th style="padding: 1rem; text-align: left; border-bottom: 1px solid #e1e5e9;">Date & Time</th>
                            <th style="padding: 1rem; text-align: left; border-bottom: 1px solid #e1e5e9;">Amount</th>
                            <th style="padding: 1rem; text-align: left; border-bottom: 1px solid #e1e5e9;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($all_bookings as $booking_item): ?>
                            <tr>
                                <td style="padding: 1rem; border-bottom: 1px solid #e1e5e9;"><?= htmlspecialchars($booking_item['username']) ?></td>
                                <td style="padding: 1rem; border-bottom: 1px solid #e1e5e9;">
                                    <strong><?= htmlspecialchars($booking_item['hall_name']) ?></strong><br>
                                    <small><?= htmlspecialchars($booking_item['location']) ?></small>
                                </td>
                                <td style="padding: 1rem; border-bottom: 1px solid #e1e5e9;">
                                    <?= date('M j, Y', strtotime($booking_item['booking_date'])) ?><br>
                                    <small><?= date('g:i A', strtotime($booking_item['start_time'])) ?> - <?= date('g:i A', strtotime($booking_item['end_time'])) ?></small>
                                </td>
                                <td style="padding: 1rem; border-bottom: 1px solid #e1e5e9;">LKR <?= number_format($booking_item['total_amount'], 2) ?></td>
                                <td style="padding: 1rem; border-bottom: 1px solid #e1e5e9;">
                                    <span class="status-badge status-<?= $booking_item['status'] ?>">
                                        <?= ucfirst($booking_item['status']) ?>
                                    </span>
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