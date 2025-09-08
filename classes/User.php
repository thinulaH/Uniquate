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

    // Create user (general use, not just admin)
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                  (username, password, email, role) 
                  VALUES (:username, :password, :email, :role)";

        $stmt = $this->conn->prepare($query);

        $this->password = password_hash($this->password, PASSWORD_DEFAULT);

        $stmt->bindParam(":username", $this->username);
        $stmt->bindParam(":password", $this->password);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":role", $this->role);

        return $stmt->execute();
    }

    // Login check
    public function login($username, $password) {
        $query = "SELECT id, username, password, role 
                  FROM " . $this->table_name . " 
                  WHERE username = :username LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":username", $username);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if (password_verify($password, $row['password'])) {
                $this->id = $row['id'];
                $this->username = $row['username'];
                $this->role = $row['role'];
                return true;
            }
        }
        return false;
    }

    // Get all users
    public function getAllUsers() {
        $query = "SELECT id, username, email, role, created_at 
                  FROM " . $this->table_name . " 
                  ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Update user
    public function update($id, $username, $email, $password, $role) {
        if ($password) {
            $query = "UPDATE " . $this->table_name . " 
                      SET username = :username, email = :email, password = :password, role = :role 
                      WHERE id = :id";
        } else {
            $query = "UPDATE " . $this->table_name . " 
                      SET username = :username, email = :email, role = :role 
                      WHERE id = :id";
        }

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":username", $username);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":role", $role);
        $stmt->bindParam(":id", $id);

        if ($password) {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt->bindParam(":password", $hashedPassword);
        }

        return $stmt->execute();
    }

    // Delete user
    public function delete($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }

    // Create admin (special method for system setup)
    public function createAdmin() {
        $query = "SELECT id FROM " . $this->table_name . " 
                  WHERE username = :username OR email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":username", $this->username);
        $stmt->bindParam(":email", $this->email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            return false; // already exists
        }

        $query = "INSERT INTO " . $this->table_name . " 
                  (username, password, email, role) 
                  VALUES (:username, :password, :email, 'admin')";

        $stmt = $this->conn->prepare($query);

        $hashedPassword = password_hash($this->password, PASSWORD_DEFAULT);

        $stmt->bindParam(":username", $this->username);
        $stmt->bindParam(":password", $hashedPassword);
        $stmt->bindParam(":email", $this->email);

        return $stmt->execute();
    }

    
}
?>
