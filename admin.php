
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

$editUser = null;

// Handle user edit (load user for editing)
if (isset($_GET['edit_user'])) {
    $edit_user_id = $_GET['edit_user'];
    // Get user data by id
    foreach ($user->getAllUsers() as $u) {
        if ($u['id'] == $edit_user_id) {
            $editUser = $u;
            break;
        }
    }
    if (!$editUser) {
        $error = "User not found.";
    }
}

// Handle unified user form (add or edit)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_form_submit'])) {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $role = $_POST['role'];
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $confirm_password = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';

    // Editing an existing user
    if (isset($_POST['user_id']) && $_POST['user_id'] !== '') {
        $user_id = $_POST['user_id'];
        if ($password !== '' && $password !== $confirm_password) {
            $error = "Passwords do not match.";
            // Re-populate editUser for form
            $editUser = [
                'id' => $user_id,
                'username' => $username,
                'email' => $email,
                'role' => $role,
                'created_at' => $_POST['created_at'] ?? ''
            ];
        } else {
            // Only update password if provided
            $updateResult = $user->update($user_id, $username, $email, $password, $role);
            if ($updateResult) {
                $message = "User updated successfully.";
                // Refresh user list after update
                $all_users = $user->getAllUsers();
                $editUser = null;
            } else {
                $error = "Failed to update user.";
                // Re-populate editUser for form
                $editUser = [
                    'id' => $user_id,
                    'username' => $username,
                    'email' => $email,
                    'role' => $role,
                    'created_at' => $_POST['created_at'] ?? ''
                ];
            }
        }
    } else {
        // Creating a new user
        if ($password === '' || $confirm_password === '') {
            $error = "Password fields are required for new users.";
        } elseif ($password !== $confirm_password) {
            $error = "Passwords do not match.";
        } else {
            $user->username = $username;
            $user->email = $email;
            $user->password = $password;
            $user->role = $role;
            // Check for unique username/email
            $existingUsers = $user->getAllUsers();
            $userExists = false;
            foreach ($existingUsers as $u) {
                if ($u['username'] === $username || $u['email'] === $email) {
                    $userExists = true;
                    break;
                }
            }
            if ($userExists) {
                $error = "User with this username or email already exists.";
            } else {
                if ($user->create()) {
                    $message = "User created successfully.";
                } else {
                    $error = "Failed to create user.";
                }
            }
        }
    }
}

// Handle user deletion
if (isset($_GET['delete_user'])) {
    $user_id = $_GET['delete_user'];

    // Use new User delete() method that returns ['success'=>bool, 'message'=>string]
    $deleteResult = $user->delete($user_id);

    if (isset($deleteResult['success']) && $deleteResult['success']) {
        $message = $deleteResult['message'] ?? 'User deleted failed';
        // Do not redirect, so alert shows
    } else {
        $error = $deleteResult['message'] ?? 'User deleted successfully';
    }
}



// Handle hall deletion
if (isset($_GET['delete_hall'])) {
    $hall_id = $_GET['delete_hall'];

    // Optional: delete hall image from server
    $hallData = $hall->getHallById($hall_id);
    if ($hallData && !empty($hallData['image_url']) && file_exists($hallData['image_url'])) {
        unlink($hallData['image_url']);
    }

    // Use new Hall delete() method that returns ['success'=>bool, 'message'=>string]
    $deleteResult = $hall->delete($hall_id);
    if (isset($deleteResult['success']) && $deleteResult['success']) {
        $message = $deleteResult['message'] ?? 'Hall deleted successfully';
        // Do not redirect, so alert shows
    } else {
        $error = $deleteResult['message'] ?? 'Failed to delete hall';
    }
}

// Edit hall
$editHall = null;

if (isset($_GET['edit_hall'])) {
    $hall_id = $_GET['edit_hall'];
    $editHall = $hall->getHallById($hall_id);
    if (!$editHall) {
        $error = "Hall not found.";
    }
}

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

    // Handle file upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        $fileTmpPath = $_FILES['image']['tmp_name'];
        $fileName = basename($_FILES['image']['name']);
        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $newFileName = uniqid('hall_', true) . '.' . $fileExt;
        $destPath = $uploadDir . $newFileName;

        if (move_uploaded_file($fileTmpPath, $destPath)) {
            $hall->image_url = $destPath;
        } else {
            $hall->image_url = '';
        }
    } else {
        $hall->image_url = '';
    }

    $hall->amenities = $_POST['amenities'];
    
    if ($hall->create()) {
        $message = 'Hall added successfully';
    } else {
        $error = 'Failed to add hall';
    }
}

// Hall update 
// Handle hall update
if (isset($_POST['update_hall'])) {
    $hall_id = $_POST['hall_id'];
    $hall->name = $_POST['name'];
    $hall->description = $_POST['description'];
    $hall->capacity = $_POST['capacity'];
    $hall->type = $_POST['type'];
    $hall->location = $_POST['location'];
    $hall->price_per_hour = $_POST['price_per_hour'];
    $hall->amenities = $_POST['amenities'];

    // Handle optional new image
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

        $fileTmpPath = $_FILES['image']['tmp_name'];
        $fileName = basename($_FILES['image']['name']);
        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $newFileName = uniqid('hall_', true) . '.' . $fileExt;
        $destPath = $uploadDir . $newFileName;

        if (move_uploaded_file($fileTmpPath, $destPath)) {
            // Delete old image
            if (!empty($_POST['current_image']) && file_exists($_POST['current_image'])) {
                unlink($_POST['current_image']);
            }
            $hall->image_url = $destPath;
        }
    } else {
        $hall->image_url = $_POST['current_image']; // keep old image
    }

if ($hall->update($hall_id)) {
    $message = "Hall updated successfully.";
    // Reset edit mode so the form returns to "Add New Hall"
    $editHall = null;
    $is_edit = false;
} else {
    $error = "Failed to update hall.";
}
}


// Refresh data after any add/update/delete
$all_bookings = $booking->getAllBookings();
$all_users = $user->getAllUsers();
$all_halls = $hall->getAllHalls();

include_once 'includes/header.php';
?>

<div class="admin-sidebar">
    <a href="#add-hall">Add New Hall</a>
    <a href="#manage-halls">Manage Halls</a>
    <a href="#manage-bookings">Manage Bookings</a>
    <a href="#user-management">User Management</a>
</div>

<div class="container admin-content">
    <div >
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
    </div>
    
    
    <!-- Add/Edit Hall Form -->
    <div id="add-hall" class="admin-task-container" >
        <div style="display: flex; align-items: center; justify-content: flex-start; gap: 1rem;">
            <h3><?= isset($editHall) && $editHall ? 'Edit Hall' : 'Add New Hall' ?></h3>
        </div>
        <br>
        <form method="POST" enctype="multipart/form-data" style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <?php
                $form_name = htmlspecialchars($editHall['name'] ?? '');
                $form_location = htmlspecialchars($editHall['location'] ?? '');
                $form_capacity = htmlspecialchars($editHall['capacity'] ?? '');
                $form_type = htmlspecialchars($editHall['type'] ?? '');
                $form_price = htmlspecialchars($editHall['price_per_hour'] ?? '');
                $form_amenities = htmlspecialchars($editHall['amenities'] ?? '');
                $form_description = htmlspecialchars($editHall['description'] ?? '');
                $form_image = htmlspecialchars($editHall['image_url'] ?? '');
                $is_edit = isset($editHall) && $editHall;
            ?>
            <?php if ($is_edit): ?>
                <input type="hidden" name="hall_id" value="<?= htmlspecialchars($editHall['id']) ?>">
                <input type="hidden" name="current_image" value="<?= htmlspecialchars($editHall['image_url']) ?>">
            <?php endif; ?>
            <div class="form-group">
                <label for="name">Hall Name</label>
                <input type="text" id="name" name="name" required value="<?= $form_name ?>">
            </div>
            <div class="form-group">
                <label for="location">Location</label>
                <input type="text" id="location" name="location" required value="<?= $form_location ?>">
            </div>
            <div class="form-group">
                <label for="capacity">Capacity</label>
                <input type="number" id="capacity" name="capacity" required value="<?= $form_capacity ?>">
            </div>
            <div class="form-group">
                <label for="type">Type</label>
                <select id="type" name="type" required>
                    <option value="Lecture Hall" <?= $form_type === 'Lecture Hall' ? 'selected' : '' ?>>Lecture Hall</option>
                    <option value="Exam Hall" <?= $form_type === 'Exam Hall' ? 'selected' : '' ?>>Exam Hall</option>
                    <option value="Conference Hall" <?= $form_type === 'Conference Hall' ? 'selected' : '' ?>>Conference Hall</option>
                    <option value="Auditorium" <?= $form_type === 'Auditorium' ? 'selected' : '' ?>>Auditorium</option>
                </select>
            </div>
            <div class="form-group">
                <label for="price_per_hour">Price per Hour (LKR)</label>
                <input type="number" id="price_per_hour" name="price_per_hour" step="1000" required value="<?= $form_price ?>">
            </div>
            <div class="form-group">
                <label for="image">Upload Image</label>
                <input type="file" id="image" name="image" accept="image/*">
                <?php if ($is_edit && $form_image): ?>
                    <div style="margin-top: 0.5rem;">
                        <img src="<?= htmlspecialchars($form_image) ?>" alt="Current Image" style="max-width: 120px; max-height: 90px; border-radius: 6px; border:1px solid #eee;">
                        <small style="display: block;">Current Image</small>
                    </div>
                <?php endif; ?>
            </div>
            <div class="form-group">
                <label for="amenities">Amenities</label>
                <input type="text" id="amenities" name="amenities" placeholder="e.g., Projector, AC, WiFi" value="<?= $form_amenities ?>">
            </div>
            <div class="form-group" style="grid-column: 1 / -1;">
                <label for="description">Description</label>
                <textarea id="description" name="description" rows="3" style="padding: 0.75rem; border: 2px solid #e1e5e9; border-radius: 10px; font-size: 1rem; width: 100%; resize: vertical;"><?= $form_description ?></textarea>
            </div>
            <div style="grid-column: 1 / -1; display: flex; justify-content: flex-end; margin-top: 1rem;">
                <?php if (isset($editHall) && $editHall): ?>
                    <div style="align-items: right; margin-right: 1rem;">
                        <a href="admin.php" class="btn btn-danger" style="width: 150px; font-size: 0.875rem;">Cancel</a>
                    </div>
                <?php endif; ?>
                <?php if ($is_edit): ?>
                    <button type="submit" name="update_hall" class="btn btn-primary" style="width: 150px; font-size: 0.875rem;">Update Hall</button>
                <?php else: ?>
                    <button type="submit" name="add_hall" class="btn btn-primary" style="width: 150px; font-size: 0.875rem;">Add Hall</button>
                <?php endif; ?>

            </div>
        </form>
    </div>

    <!-- Manage Halls -->
     <div id="manage-halls" class="admin-task-container" style="background: white; padding:2rem; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.1); margin: 2rem 0;">
        <h3> Manage Halls</h3>
        <br>
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; border-radius: 10px; overflow: hidden; ">
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
                            <td style="padding: 1rem; border-bottom: 1px solid #e1e5e9;">LKR <?= number_format($hall['price_per_hour'], 2) ?></td>
                            <td style="padding: 1rem; border-bottom: 1px solid #e1e5e9; align-items: center; display: flex; flex-direction: column;">
                                <a href="admin.php?edit_hall=<?= $hall['id'] ?>" class="btn btn-secondary form-button" style="margin:2px 0; font-size: 0.875rem;">Edit</a>
                                <a href="admin.php?delete_hall=<?= $hall['id'] ?>" class="btn btn-danger form-button" style="margin:2px 0; font-size: 0.875rem;" onclick="return confirm('Are you sure you want to delete this hall?')">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
     </div>

    
    
    <!-- Manage Bookings -->
    <div id="manage-bookings" class="admin-task-container" >
        <h3>Manage Bookings</h3><br>
        
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
                                <td style="padding: 1rem; border-bottom: 1px solid #e1e5e9;">LKR <?= number_format($booking_item['total_amount'], 2) ?></td>
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

    <!-- Unified Add/Edit User Form -->
    <div id="user-management" class="admin-task-container" >
        <h3>User Management</h3><br>
        <div style="background: #f9f9f9; padding: 1.5rem; border-radius: 10px; margin-bottom: 2rem; box-shadow: 0 2px 8px rgba(0,0,0,0.05); max-width: 100%;">
            <h4><?= $editUser ? 'Edit User' : 'Add New User' ?></h4>
            <form method="POST" style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <?php
                    // Prepopulate form values
                    $form_username = '';
                    $form_email = '';
                    $form_role = 'user';
                    $form_user_id = '';
                    $form_created_at = '';
                    if ($editUser) {
                        $form_username = htmlspecialchars($editUser['username']);
                        $form_email = htmlspecialchars($editUser['email']);
                        $form_role = $editUser['role'];
                        $form_user_id = htmlspecialchars($editUser['id']);
                        $form_created_at = htmlspecialchars($editUser['created_at']);
                    } else {
                        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_form_submit'])) {
                            $form_username = htmlspecialchars($_POST['username'] ?? '');
                            $form_email = htmlspecialchars($_POST['email'] ?? '');
                            $form_role = $_POST['role'] ?? 'user';
                        }
                    }
                ?>
                <?php if ($editUser): ?>
                    <input type="hidden" name="user_id" value="<?= $form_user_id ?>">
                    <input type="hidden" name="created_at" value="<?= $form_created_at ?>">
                <?php endif; ?>
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" required value="<?= $form_username ?>" style="padding: 0.75rem; border: 2px solid #e1e5e9; border-radius: 10px; font-size: 1rem; width: 100%;">
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required value="<?= $form_email ?>" style="padding: 0.75rem; border: 2px solid #e1e5e9; border-radius: 10px; font-size: 1rem; width: 100%;">
                </div>
                <div class="form-group">
                    <label for="role">Role</label>
                    <select id="role" name="role" required style="padding: 0.75rem; border: 2px solid #e1e5e9; border-radius: 10px; font-size: 1rem; width: 100%;">
                        <option value="user" <?= $form_role === 'user' ? 'selected' : '' ?>>User</option>
                        <option value="admin" <?= $form_role === 'admin' ? 'selected' : '' ?>>Admin</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="password"><?= $editUser ? 'New Password <small>(leave blank to keep unchanged)</small>' : 'Password' ?></label>
                    <input type="password" id="password" name="password" <?= $editUser ? '' : 'required' ?> style="padding: 0.75rem; border: 2px solid #e1e5e9; border-radius: 10px; font-size: 1rem; width: 100%;">
                </div>
                <div class="form-group">
                    <label for="confirm_password">Confirm Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" <?= $editUser ? '' : 'required' ?> style="padding: 0.75rem; border: 2px solid #e1e5e9; border-radius: 10px; font-size: 1rem; width: 100%;">
                </div>
                <div style="grid-column: 1 / -1; display: flex; justify-content: flex-end; gap: 1rem; margin-top: 1rem;">
                    <?php if ($editUser): ?>
                        <a href="admin.php#user-management" class="btn btn-danger" style="width: 120px; font-size: 0.875rem;">Cancel</a>
                        <button type="submit" name="user_form_submit" class="btn btn-primary" style="width: 150px; font-size: 0.875rem;">Update User</button>
                    <?php else: ?>
                        <button type="submit" name="user_form_submit" class="btn btn-primary" style="width: 150px; font-size: 0.875rem;">Add User</button>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    <!-- User Management -->
    <div id="user-management" class="admin-task-container" >
        <h3>User Management</h3><br>
        
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; border-radius: 10px; overflow: hidden;">
                <thead>
                    <tr style="background: var(--light-tan);">
                        <th style="padding: 1rem; text-align: left; border-bottom: 1px solid #e1e5e9;">Username</th>
                        <th style="padding: 1rem; text-align: left; border-bottom: 1px solid #e1e5e9;">Email</th>
                        <th style="padding: 1rem; text-align: left; border-bottom: 1px solid #e1e5e9;">Role</th>
                        <th style="padding: 1rem; text-align: left; border-bottom: 1px solid #e1e5e9;">Registered</th>
                        <th style="padding: 1rem; text-align: left; border-bottom: 1px solid #e1e5e9;">Actions</th>
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
                            <td style="padding: 1rem; border-bottom: 1px solid #e1e5e9; align-items: center; display: flex; flex-direction: column;">
                                <a href="admin.php?edit_user=<?= $user_item['id'] ?>#user-management" class="btn btn-secondary form-button" style="margin:2px 0; font-size: 0.875rem;">Edit</a>
                                <a href="admin.php?delete_user=<?= $user_item['id'] ?>" class="btn btn-danger form-button" style="margin:2px 0; font-size: 0.875rem;" onclick="return confirm('Are you sure you want to delete this user?')">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</div>

<?php include_once 'includes/footer.php'; ?>
