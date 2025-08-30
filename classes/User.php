<?php
    class User {
        private $conn;
        private $table_name = "users";

        public $id;
        public $username;
        public $password;
        public $email;
        public $role;
        public $created_at;

        public function __construct($db) {
            $this->conn = $db;
        }

        public function create() {
            $query = "INSERT INTO " . $this->table_name . " 
                    SET username=:username, password=:password, email=:email, role=:role";
            
            $stmt = $this->conn->prepare($query);
            
            $this->password = password_hash($this->password, PASSWORD_DEFAULT);
            
            $stmt->bindParam(":username", $this->username);
            $stmt->bindParam(":password", $this->password);
            $stmt->bindParam(":email", $this->email);
            $stmt->bindParam(":role", $this->role);
            
            if($stmt->execute()) {
                return true;
            }
            return false;
        }

        public function login($username, $password) {
            $query = "SELECT id, username, password, role FROM " . $this->table_name . " WHERE username = :username";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":username", $username);
            $stmt->execute();
            
            if($stmt->rowCount() > 0) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                if(password_verify($password, $row['password'])) {
                    $this->id = $row['id'];
                    $this->username = $row['username'];
                    $this->role = $row['role'];
                    return true;
                }
            }
            return false;
        }

        public function getAllUsers() {
            $query = "SELECT id, username, email, role, created_at FROM " . $this->table_name . " ORDER BY created_at DESC";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public function createAdmin() {
            // Check if username or email already exists
            $query = "SELECT id FROM " . $this->table_name . " WHERE username = :username OR email = :email";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":username", $this->username);
            $stmt->bindParam(":email", $this->email);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                // Username or email already exists
                return false;
            }

            // Proceed to insert
            $query = "INSERT INTO " . $this->table_name . " 
                      (username, password, email, role) 
                      VALUES (:username, :password, :email, 'admin')";

            $stmt = $this->conn->prepare($query);

            $hashedPassword = password_hash($this->password, PASSWORD_DEFAULT);

            $stmt->bindParam(":username", $this->username);
            $stmt->bindParam(":password", $hashedPassword);
            $stmt->bindParam(":email", $this->email);

            if ($stmt->execute()) {
                return true;
            }
            return false;
        }
    }
?>