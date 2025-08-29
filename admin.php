<?php
// admin.php
include_once 'config/database.php';
include_once 'classes/User.php';
include_once 'classes/Hall.php';
include_once 'classes/Booking.php';
include_once 'auth/session.php';

requireAdmin();

$database = new Database();
$db = $database->getConnection();
$user = new User($db);
$hall = new Hall($db);
$booking = new Booking($db);

$message = '';
$error = '';

// Handle booking status updates
if (isset($_POST['update_booking_status'])) {
    $booking_id = $_POST['booking_id'];
    $new_status = $_POST['status'];
    
    if ($booking->updateStatus($booking_id, $new_status)) {
        $message = 'Booking status updated successfully';
    } else {
        $error = 'Failed to update booking status';
    }
}

// Handle new hall creation
if (isset($_POST['add_hall'])) {
    $hall->name = $_POST['name'];
    $hall->description = $_POST['description'];
    $hall->capacity = $_POST['capacity'];
    $hall->type = $_POST['type'];
    $hall->location = $_POST['location'];
    $hall->price_per_hour = $_POST['price_per_hour'];
    $hall->image_url = $_POST['image_url'];
    $hall->amenities = $_POST['amenities'];
    
    if ($hall->create()) {
        $message = 'Hall added successfully';
    } else {
        $error = 'Failed to add hall';
    }
}

$all_bookings = $booking->getAllBookings();
$all_users = $user->getAllUsers();
$all_halls = $hall->getAllHalls();

include_once 'includes/header.php';
?>

<div class="container">
    <div style="margin: 2rem 0;">
        <h1>Admin Panel</h1>
        <p>Manage halls, bookings, and users</p>
    </div>
    
    <?php if ($message): ?>
        <div class="alert alert-success"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>
    
    <?php if ($error): ?>
        <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    
    <!-- Quick Stats -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 2rem; margin: 2rem 0;">
        <div style="background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.1); text-align: center;">
            <h3 style="color: var(--purple);"><?= count($all_halls) ?></h3>
            <p>Total Halls</p>
        </div>
        <div style="background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.1); text-align: center;">
            <h3 style="color: var(--tan);"><?= count($all_bookings) ?></h3>
            <p>Total Bookings</p>
        </div>
        <div style="background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.1); text-align: center;">
            <h3 style="color: var(--purple);"><?= count($all_users) ?></h3>
            <p>Registered Users</p>
        </div>
        <div style="background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.1); text-align: center;">
            <h3 style="color: var(--tan);"><?= count(array_filter($all_bookings, function($b) { return $b['status'] === 'pending'; })) ?></h3>
            <p>Pending Requests</p>
        </div>
    </div>
    
    <!-- Add New Hall Form -->
    <div style="background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.1); margin: 2rem 0;">
        <h3>Add New Hall</h3>
        <form method="POST" style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <div class="form-group">
                <label for="name">Hall Name</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="location">Location</label>
                <input type="text" id="location" name="location" required>
            </div>
            <div class="form-group">
                <label for="capacity">Capacity</label>
                <input type="number" id="capacity" name="capacity" required>
            </div>
            <div class="form-group">
                <label for="type">Type</label>
                <select id="type" name="type" required>
                    <option value="Lecture Hall">Lecture Hall</option>
                    <option value="Exam Hall">Exam Hall</option>
                    <option value="Conference Hall">Conference Hall</option>
                    <option value="Auditorium">Auditorium</option>
                </select>
            </div>
            <div class="form-group">
                <label for="price_per_hour">Price per Hour ($)</label>
                <input type="number" id="price_per_hour" name="price_per_hour" step="0.01" required>
            </div>
            <div class="form-group">
                <label for="image_url">Image URL (optional)</label>
                <input type="url" id="image_url" name="image_url">
            </div>
            <div class="form-group">
                <label for="amenities">Amenities</label>
                <input type="text" id="amenities" name="amenities" placeholder="e.g., Projector, AC, WiFi">
            </div>
            <div class="form-group" style="grid-column: 1 / -1;">
                <label for="description">Description</label>
                <textarea id="description" name="description" rows="3" style="padding: 0.75rem; border: 2px solid #e1e5e9; border-radius: 10px; font-size: 1rem; width: 100%; resize: vertical;"></textarea>
            </div>
            <div style="grid-column: 1 / -1;">
                <button type="submit" name="add_hall" class="btn btn-primary">Add Hall</button>
            </div>
        </form>
    </div>

    <!-- Manage Halls -->
     <div style= "background: white; padding:2rem; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.1); margin: 2rem 0;">
        <h3> Manage Halls</h3>
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: var(--light-tan);">
                        <th style="padding: 1rem; text-align: left; border-bottom: 1px solid #e1e5e9;">Hall Name</th>
                        <th style="padding: 1rem; text-align: left; border-bottom: 1px solid #e1e5e9;">Location</th>
                        <th style="padding: 1rem; text-align: left; border-bottom: 1px solid #e1e5e9;">Capacity</th>
                        <th style="padding: 1rem; text-align: left; border-bottom: 1px solid #e1e5e9;">Type</th>
                        <th style="padding: 1rem; text-align: left; border-bottom: 1px solid #e1e5e9;">Price per Hour</th>
                        <th style="padding: 1rem; text-align: left; border-bottom: 1px solid #e1e5e9;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($all_halls as $hall): ?>
                        <tr>
                            <td style="padding: 1rem; border-bottom: 1px solid #e1e5e9;"><?= htmlspecialchars($hall['name']) ?></td>
                            <td style="padding: 1rem; border-bottom: 1px solid #e1e5e9;"><?= htmlspecialchars($hall['location']) ?></td>
                            <td style="padding: 1rem; border-bottom: 1px solid #e1e5e9;"><?= htmlspecialchars($hall['capacity']) ?></td>
                            <td style="padding: 1rem; border-bottom: 1px solid #e1e5e9;"><?= htmlspecialchars($hall['type']) ?></td>
                            <td style="padding: 1rem; border-bottom: 1px solid #e1e5e9;">$<?= number_format($hall['price_per_hour'], 2) ?></td>
                            <td style="padding: 1rem; border-bottom: 1px solid #e1e5e9;">
                                <a href="admin.php?edit_hall=<?= $hall['id'] ?>" class="btn btn-secondary">Edit</a>
                                <a href="admin.php?delete_hall=<?= $hall['id'] ?>" class="btn btn-danger">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
     </div>
    
    <!-- Manage Bookings -->
    <div style="background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.1); margin: 2rem 0;">
        <h3>Manage Bookings</h3>
        
        <?php if (empty($all_bookings)): ?>
            <p>No bookings found.</p>
        <?php else: ?>
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background: var(--light-tan);">
                            <th style="padding: 1rem; text-align: left; border-bottom: 1px solid #e1e5e9;">User</th>
                            <th style="padding: 1rem; text-align: left; border-bottom: 1px solid #e1e5e9;">Hall</th>
                            <th style="padding: 1rem; text-align: left; border-bottom: 1px solid #e1e5e9;">Date & Time</th>
                            <th style="padding: 1rem; text-align: left; border-bottom: 1px solid #e1e5e9;">Amount</th>
                            <th style="padding: 1rem; text-align: left; border-bottom: 1px solid #e1e5e9;">Status</th>
                            <th style="padding: 1rem; text-align: left; border-bottom: 1px solid #e1e5e9;">Actions</th>
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
                                <td style="padding: 1rem; border-bottom: 1px solid #e1e5e9;">$<?= number_format($booking_item['total_amount'], 2) ?></td>
                                <td style="padding: 1rem; border-bottom: 1px solid #e1e5e9;">
                                    <span class="status-badge status-<?= $booking_item['status'] ?>">
                                        <?= ucfirst($booking_item['status']) ?>
                                    </span>
                                </td>
                                <td style="padding: 1rem; border-bottom: 1px solid #e1e5e9;">
                                    <form method="POST" style="display: inline;">
                                        <input type="hidden" name="booking_id" value="<?= $booking_item['id'] ?>">
                                        <select name="status" onchange="this.form.submit()" style="padding: 0.25rem; border-radius: 5px; border: 1px solid #ccc;">
                                            <option value="pending" <?= $booking_item['status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                                            <option value="confirmed" <?= $booking_item['status'] === 'confirmed' ? 'selected' : '' ?>>Confirmed</option>
                                            <option value="cancelled" <?= $booking_item['status'] === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                                        </select>
                                        <input type="hidden" name="update_booking_status" value="1">
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
    
    <!-- User Management -->
    <div style="background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.1); margin: 2rem 0;">
        <h3>User Management</h3>
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
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
</div>

<?php include_once 'includes/footer.php'; ?>
