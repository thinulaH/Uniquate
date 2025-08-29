<?php
    class Hall {
        private $conn;
        private $table_name = "halls";

        public $id;
        public $name;
        public $description;
        public $capacity;
        public $location;
        public $price_per_hour;
        public $image_url;
        public $amenities;
        public $created_at;
        public $type;

        public function __construct($db) {
            $this->conn = $db;
        }

        public function create() {
            $query = "INSERT INTO " . $this->table_name . " 
                    SET name=:name, description=:description, capacity=:capacity, 
                        location=:location, price_per_hour=:price_per_hour, 
                        image_url=:image_url, amenities=:amenities, type=:type";
            
            $stmt = $this->conn->prepare($query);
            
            $stmt->bindParam(":name", $this->name);
            $stmt->bindParam(":description", $this->description);
            $stmt->bindParam(":capacity", $this->capacity);
            $stmt->bindParam(":location", $this->location);
            $stmt->bindParam(":price_per_hour", $this->price_per_hour);
            $stmt->bindParam(":image_url", $this->image_url);
            $stmt->bindParam(":amenities", $this->amenities);
            $stmt->bindParam(":type", $this->type);
            
            return $stmt->execute();
        }

        public function getAllHalls() {
            $query = "SELECT * FROM " . $this->table_name . " ORDER BY name ASC";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public function getHallById($id) {
            $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":id", $id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

        public function searchHalls($search_term, $capacity = null, $type = null) {
            $query = "SELECT * FROM " . $this->table_name . " WHERE 
                    (name LIKE :search OR location LIKE :search OR description LIKE :search)";
            
            if ($capacity) {
                $query .= " AND capacity >= :capacity";
            }
            
            if (!empty($type)) {
                $query .= " AND type = :type";
            }
            
            $query .= " ORDER BY name ASC";
            
            $stmt = $this->conn->prepare($query);
            $search_param = "%" . $search_term . "%";
            $stmt->bindParam(":search", $search_param);
            
            if ($capacity) {
                $stmt->bindParam(":capacity", $capacity);
            }
            
            if (!empty($type)) {
                $stmt->bindParam(":type", $type);
            }
            
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    }
?>
