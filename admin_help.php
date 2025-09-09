<?php
// admin_help.php
include_once 'config/database.php';
include_once 'classes/User.php';
include_once 'auth/session.php';

requireAdmin();
include_once 'includes/header.php';
?>
d

<div class="container admin-content">
    <div>
        <h1>Admin Help & Support</h1>
        <p>Welcome to the help page for administrators. Here you can find guidelines 
           on how to use different features of the admin panel effectively.</p>
    </div>

    <!-- General Notes -->
    <div class="admin-task-container" style="background:white; padding:2rem; border-radius:15px; margin:1.5rem 0;">
        <h3>General Guidelines</h3>
        <ul style="line-height:1.8;">
            <li>Always log out after finishing your work to keep the system secure.</li>
            <li>Check pending booking requests regularly and update their status.</li>
            <li>When uploading hall images, ensure they are clear and under 2MB in size.</li>
            <li>Use descriptive hall names and details so users can easily identify them.</li>
        </ul>
    </div>

    <!-- Add New Hall -->
    <div class="admin-task-container" style="background:white; padding:2rem; border-radius:15px; margin:1.5rem 0;">
        <h3>Adding a New Hall</h3>
        <p>To add a hall:</p>
        <ol style="line-height:1.8;">
            <li>Go to <strong>“Add New Hall”</strong> section in the Admin Panel.</li>
            <li>Fill in hall details (name, location, capacity, type, price, amenities).</li>
            <li>Upload an image of the hall (optional but recommended).</li>
            <li>Click <strong>“Add Hall”</strong> to save.</li>
        </ol>
        <p><em>Tip: Provide accurate capacity and amenities for user clarity.</em></p>
    </div>

    <!-- Manage Halls -->
    <div class="admin-task-container" style="background:white; padding:2rem; border-radius:15px; margin:1.5rem 0;">
        <h3>Managing Halls</h3>
        <ul style="line-height:1.8;">
            <li>In <strong>“Manage Halls”</strong>, you can edit or delete existing halls.</li>
            <li>Editing lets you update details or replace hall images.</li>
            <li>Deleting removes the hall permanently from the system (action cannot be undone).</li>
        </ul>
    </div>

    <!-- Manage Bookings -->
    <div class="admin-task-container" style="background:white; padding:2rem; border-radius:15px; margin:1.5rem 0;">
        <h3>Managing Bookings</h3>
        <p>In the <strong>“Manage Bookings”</strong> section, you can:</p>
        <ul style="line-height:1.8;">
            <li>View all bookings made by users.</li>
            <li>Update the status of bookings:
                <ul>
                    <li><strong>Pending</strong> – New requests awaiting review.</li>
                    <li><strong>Confirmed</strong> – Approved bookings.</li>
                    <li><strong>Cancelled</strong> – Rejected or cancelled by admin.</li>
                </ul>
            </li>
            <li>Ensure pending requests are processed quickly for better user experience.</li>
        </ul>
    </div>

    <!-- Create Admin -->
    <div class="admin-task-container" style="background:white; padding:2rem; border-radius:15px; margin:1.5rem 0;">
        <h3>Creating New Admins</h3>
        <p>To create another administrator account:</p>
        <ol style="line-height:1.8;">
            <li>Go to <strong>“Create Admin”</strong> section.</li>
            <li>Enter a username, email, and password.</li>
            <li>Confirm the password and click <strong>“Add Admin”</strong>.</li>
            <li>The new admin will be able to log in with their credentials.</li>
        </ol>
    </div>

    <!-- User Management -->
    <div class="admin-task-container" style="background:white; padding:2rem; border-radius:15px; margin:1.5rem 0;">
        <h3>User Management</h3>
        <ul style="line-height:1.8;">
            <li>View all registered users including their username, email, and role.</li>
            <li>Admins are marked separately from regular users.</li>
            <li>Monitor new user sign-ups to ensure legitimate use of the system.</li>
        </ul>
    </div>

    <!-- Contact Support -->
    <div class="admin-task-container" style="background:white; padding:2rem; border-radius:15px; margin:1.5rem 0;">
        <h3>Need Further Help?</h3>
        <p>If you encounter any issues that you cannot resolve:</p>
        <ul style="line-height:1.8;">
            <li>Contact the system developer or IT support team.</li>
            <li>Email: <a href="mailto:202320224@stu.cmb.lk">202320224@stu.cmb.lk</a></li>
            <li>Phone: +94-705953856</li>
        </ul>
    </div>
</div>

<?php include_once 'includes/footer.php'; ?>
