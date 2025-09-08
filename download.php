<?php
require_once 'vendor/autoload.php';
include_once 'config/database.php';
include_once 'classes/User.php';
include_once 'classes/Hall.php';
include_once 'classes/Booking.php';

use Dompdf\Dompdf;

// DB setup
$database = new Database();
$db = $database->getConnection();
$user = new User($db);
$hall = new Hall($db);
$booking = new Booking($db);

$all_bookings = $booking->getAllBookings();
$all_users = $user->getAllUsers();
$all_halls = $hall->getAllHalls();

// Prepare HTML for PDF
$html = '<h1 style="text-align:center;">Admin Reports</h1>';
$html .= '<p style="text-align:center;">Generated on '.date("M j, Y, g:i A").'</p>';

// Summary
$html .= '<h2>Summary</h2>';
$html .= '<ul>';
$html .= '<li>Total Users: '.count($all_users).'</li>';
$html .= '<li>Total Halls: '.count($all_halls).'</li>';
$html .= '<li>Total Bookings: '.count($all_bookings).'</li>';
$html .= '</ul>';

// Users
$html .= '<h2>Users</h2>';
$html .= '<table border="1" cellspacing="0" cellpadding="5" width="100%">';
$html .= '<thead><tr><th>Username</th><th>Email</th><th>Role</th><th>Registered</th></tr></thead><tbody>';
foreach ($all_users as $u) {
    $html .= '<tr>
                <td>'.$u['username'].'</td>
                <td>'.$u['email'].'</td>
                <td>'.$u['role'].'</td>
                <td>'.date("M j, Y", strtotime($u['created_at'])).'</td>
              </tr>';
}
$html .= '</tbody></table>';

// Halls
$html .= '<h2>Halls</h2>';
$html .= '<table border="1" cellspacing="0" cellpadding="5" width="100%">';
$html .= '<thead><tr><th>Name</th><th>Location</th><th>Capacity</th><th>Type</th><th>Price</th></tr></thead><tbody>';
foreach ($all_halls as $h) {
    $html .= '<tr>
                <td>'.$h['name'].'</td>
                <td>'.$h['location'].'</td>
                <td>'.$h['capacity'].'</td>
                <td>'.$h['type'].'</td>
                <td>LKR '.number_format($h['price_per_hour'], 2).'</td>
              </tr>';
}
$html .= '</tbody></table>';

// Bookings
$html .= '<h2>Bookings</h2>';
if (empty($all_bookings)) {
    $html .= '<p>No bookings found.</p>';
} else {
    $html .= '<table border="1" cellspacing="0" cellpadding="5" width="100%">';
    $html .= '<thead><tr><th>User</th><th>Hall</th><th>Date</th><th>Time</th><th>Amount</th><th>Status</th></tr></thead><tbody>';
    foreach ($all_bookings as $b) {
        $html .= '<tr>
                    <td>'.$b['username'].'</td>
                    <td>'.$b['hall_name'].' ('.$b['location'].')</td>
                    <td>'.date("M j, Y", strtotime($b['booking_date'])).'</td>
                    <td>'.date("g:i A", strtotime($b['start_time'])).' - '.date("g:i A", strtotime($b['end_time'])).'</td>
                    <td>LKR '.number_format($b['total_amount'], 2).'</td>
                    <td>'.ucfirst($b['status']).'</td>
                  </tr>';
    }
    $html .= '</tbody></table>';
}

// Generate PDF
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'landscape'); // or portrait
$dompdf->render();
$dompdf->stream("admin_report.pdf", ["Attachment" => 1]); // 1 = download, 0 = preview
?>
