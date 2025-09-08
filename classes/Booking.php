<?php
    class Booking {
        private $conn;
        private $table_name = "bookings";

        public $id;
        public $user_id;
        public $hall_id;
        public $booking_date;
        public $start_time;
        public $end_time;
        public $purpose;
        public $total_amount;
        public $status;
        public $created_at;

        public function __construct($db) {
            $this->conn = $db;
        }

        public function create() {
            // Check if hall is available
            if (!$this->isAvailable()) {
                return false;
            }

            $query = "INSERT INTO " . $this->table_name . " 
                    SET user_id=:user_id, hall_id=:hall_id, booking_date=:booking_date, 
                        start_time=:start_time, end_time=:end_time, purpose=:purpose, 
                        total_amount=:total_amount, status=:status";
            
            $stmt = $this->conn->prepare($query);
            
            $stmt->bindParam(":user_id", $this->user_id);
            $stmt->bindParam(":hall_id", $this->hall_id);
            $stmt->bindParam(":booking_date", $this->booking_date);
            $stmt->bindParam(":start_time", $this->start_time);
            $stmt->bindParam(":end_time", $this->end_time);
            $stmt->bindParam(":purpose", $this->purpose);
            $stmt->bindParam(":total_amount", $this->total_amount);
            $stmt->bindParam(":status", $this->status);
            
            return $stmt->execute();
        }

        public function isAvailable() {
            $query = "SELECT COUNT(*) as count FROM " . $this->table_name . " 
                    WHERE hall_id = :hall_id AND booking_date = :booking_date 
                    AND status != 'cancelled' 
                    AND ((start_time <= :start_time AND end_time > :start_time) 
                        OR (start_time < :end_time AND end_time >= :end_time) 
                        OR (start_time >= :start_time AND end_time <= :end_time))";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":hall_id", $this->hall_id);
            $stmt->bindParam(":booking_date", $this->booking_date);
            $stmt->bindParam(":start_time", $this->start_time);
            $stmt->bindParam(":end_time", $this->end_time);
            $stmt->execute();
            
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row['count'] == 0;
        }

        public function getUserBookings($user_id) {
            $query = "SELECT b.*, h.name as hall_name, h.location 
                    FROM " . $this->table_name . " b 
                    JOIN halls h ON b.hall_id = h.id 
                    WHERE b.user_id = :user_id 
                    ORDER BY b.booking_date DESC, b.start_time DESC";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":user_id", $user_id);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public function getAllBookings() {
            $query = "SELECT b.*, u.username, h.name as hall_name, h.location 
                    FROM " . $this->table_name . " b 
                    JOIN users u ON b.user_id = u.id 
                    JOIN halls h ON b.hall_id = h.id 
                    ORDER BY b.booking_date DESC, b.start_time DESC";
            
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public function updateStatus($booking_id, $status) {
            $query = "UPDATE " . $this->table_name . " SET status = :status WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":status", $status);
            $stmt->bindParam(":id", $booking_id);
            return $stmt->execute();
        }
        
        /**
         * Checks if a booking can be modified/cancelled (more than 2 days away from today)
         * @param int $booking_id
         * @return bool
         */
        public function canModifyBooking($booking_id) {
            $query = "SELECT booking_date FROM " . $this->table_name . " WHERE id = :id LIMIT 1";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":id", $booking_id);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$row || empty($row['booking_date'])) {
                return false;
            }
            $bookingDate = new DateTime($row['booking_date']);
            $today = new DateTime();
            $interval = $today->diff($bookingDate);
            // bookingDate > today and at least 2 days difference
            return ($bookingDate > $today) && ($interval->days > 2);
        }

        public function cancelBooking($booking_id) {
            if (!$this->canModifyBooking($booking_id)) {
                // Not allowed to cancel
                return false;
            }
            return $this->updateStatus($booking_id, 'cancelled');
        }
    }
?>