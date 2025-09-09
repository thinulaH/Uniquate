<?php

// Create a booking
function createBooking($conn, $user_id, $hall_id, $booking_date, $start_time, $end_time, $purpose, $total_amount, $status) {
    if (!isHallAvailable($conn, $hall_id, $booking_date, $start_time, $end_time)) {
        return false; // Hall not available
    }

    $query = "INSERT INTO bookings 
              (user_id, hall_id, booking_date, start_time, end_time, purpose, total_amount, status) 
              VALUES (:user_id, :hall_id, :booking_date, :start_time, :end_time, :purpose, :total_amount, :status)";
    
    $stmt = $conn->prepare($query);
    $stmt->bindParam(":user_id", $user_id);
    $stmt->bindParam(":hall_id", $hall_id);
    $stmt->bindParam(":booking_date", $booking_date);
    $stmt->bindParam(":start_time", $start_time);
    $stmt->bindParam(":end_time", $end_time);
    $stmt->bindParam(":purpose", $purpose);
    $stmt->bindParam(":total_amount", $total_amount);
    $stmt->bindParam(":status", $status);
    
    return $stmt->execute();
}

// Check if hall is available
function isHallAvailable($conn, $hall_id, $booking_date, $start_time, $end_time) {
    $query = "SELECT COUNT(*) as count FROM bookings 
              WHERE hall_id = :hall_id AND booking_date = :booking_date 
              AND status != 'cancelled' 
              AND ((start_time <= :start_time AND end_time > :start_time) 
                OR (start_time < :end_time AND end_time >= :end_time) 
                OR (start_time >= :start_time AND end_time <= :end_time))";
    
    $stmt = $conn->prepare($query);
    $stmt->bindParam(":hall_id", $hall_id);
    $stmt->bindParam(":booking_date", $booking_date);
    $stmt->bindParam(":start_time", $start_time);
    $stmt->bindParam(":end_time", $end_time);
    $stmt->execute();
    
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row['count'] == 0;
}

// Get bookings of a specific user
function getUserBookings($conn, $user_id) {
    $query = "SELECT b.*, h.name as hall_name, h.location 
              FROM bookings b 
              JOIN halls h ON b.hall_id = h.id 
              WHERE b.user_id = :user_id 
              ORDER BY b.booking_date DESC, b.start_time DESC";
    
    $stmt = $conn->prepare($query);
    $stmt->bindParam(":user_id", $user_id);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Get all bookings (admin view)
function getAllBookings($conn) {
    $query = "SELECT b.*, u.username, h.name as hall_name, h.location 
              FROM bookings b 
              JOIN users u ON b.user_id = u.id 
              JOIN halls h ON b.hall_id = h.id 
              ORDER BY b.booking_date DESC, b.start_time DESC";
    
    $stmt = $conn->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Update booking status
function updateBookingStatus($conn, $booking_id, $status) {
    $query = "UPDATE bookings SET status = :status WHERE id = :id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(":status", $status);
    $stmt->bindParam(":id", $booking_id);
    return $stmt->execute();
}

// Check if booking can be modified/cancelled (must be > 2 days away)
function canModifyBooking($conn, $booking_id) {
    $query = "SELECT booking_date FROM bookings WHERE id = :id LIMIT 1";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(":id", $booking_id);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$row || empty($row['booking_date'])) {
        return false;
    }
    $bookingDate = new DateTime($row['booking_date']);
    $today = new DateTime();
    $interval = $today->diff($bookingDate);
    return ($bookingDate > $today) && ($interval->days > 2);
}

// Cancel booking
function cancelBooking($conn, $booking_id) {
    if (!canModifyBooking($conn, $booking_id)) {
        return false;
    }
    return updateBookingStatus($conn, $booking_id, 'cancelled');
}
?>