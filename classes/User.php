<?php
// === User Functions (procedural) ===

// Create a user
function createUser($conn, $username, $password, $email, $role) {
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $query = "INSERT INTO users (username, password, email, role) 
              VALUES (:username, :password, :email, :role)";
    
    $stmt = $conn->prepare($query);
    $stmt->bindParam(":username", $username);
    $stmt->bindParam(":password", $hashedPassword);
    $stmt->bindParam(":email", $email);
    $stmt->bindParam(":role", $role);

    return $stmt->execute();
}

// Check login credentials
function loginUser($conn, $username, $password) {
    $query = "SELECT id, username, password, role FROM users 
              WHERE username = :username LIMIT 1";
    
    $stmt = $conn->prepare($query);
    $stmt->bindParam(":username", $username);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (password_verify($password, $row['password'])) {
            // Return user data if login successful
            return [
                'id' => $row['id'],
                'username' => $row['username'],
                'role' => $row['role']
            ];
        }
    }
    return false;
}

// Get all users
function getAllUsers($conn) {
    $query = "SELECT id, username, email, role, created_at 
              FROM users ORDER BY created_at DESC";
    
    $stmt = $conn->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Update user
function updateUser($conn, $id, $username, $email, $role, $password = null) {
    if ($password) {
        $query = "UPDATE users SET username = :username, email = :email, password = :password, role = :role 
                  WHERE id = :id";
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    } else {
        $query = "UPDATE users SET username = :username, email = :email, role = :role 
                  WHERE id = :id";
    }

    $stmt = $conn->prepare($query);
    $stmt->bindParam(":username", $username);
    $stmt->bindParam(":email", $email);
    $stmt->bindParam(":role", $role);
    $stmt->bindParam(":id", $id);

    if ($password) {
        $stmt->bindParam(":password", $hashedPassword);
    }

    return $stmt->execute();
}

// Delete user
function deleteUser($conn, $id) {
    $query = "DELETE FROM users WHERE id = :id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(":id", $id);
    return $stmt->execute();
}

// Create admin (for initial system setup)
function createAdmin($conn, $username, $password, $email) {
    // Check if username or email already exists
    $query = "SELECT id FROM users WHERE username = :username OR email = :email";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(":username", $username);
    $stmt->bindParam(":email", $email);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        return false; // Admin already exists
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $query = "INSERT INTO users (username, password, email, role) 
              VALUES (:username, :password, :email, 'admin')";
    
    $stmt = $conn->prepare($query);
    $stmt->bindParam(":username", $username);
    $stmt->bindParam(":password", $hashedPassword);
    $stmt->bindParam(":email", $email);

    return $stmt->execute();
}
?>