<?php
// === Hall Functions (procedural) ===

// Create a hall (with optional image upload)
function createHall($conn, $name, $description, $capacity, $location, $price_per_hour, $amenities, $type, $imageFile = null) {
    $image_url = null;

    // Handle image upload if provided
    if ($imageFile && $imageFile['error'] === UPLOAD_ERR_OK) {
        $uploadDir = __DIR__ . '/../uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        $fileName = time() . "_" . basename($imageFile['name']);
        $targetPath = $uploadDir . $fileName;

        if (move_uploaded_file($imageFile['tmp_name'], $targetPath)) {
            $image_url = 'uploads/' . $fileName;
        }
    }

    $query = "INSERT INTO halls 
              (name, description, capacity, location, price_per_hour, image_url, amenities, type)
              VALUES (:name, :description, :capacity, :location, :price_per_hour, :image_url, :amenities, :type)";
    
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':capacity', $capacity);
    $stmt->bindParam(':location', $location);
    $stmt->bindParam(':price_per_hour', $price_per_hour);
    $stmt->bindParam(':image_url', $image_url);
    $stmt->bindParam(':amenities', $amenities);
    $stmt->bindParam(':type', $type);

    return $stmt->execute();
}

// Get all halls
function getAllHalls($conn) {
    $query = "SELECT * FROM halls ORDER BY name ASC";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Get hall by ID
function getHallById($conn, $id) {
    $query = "SELECT * FROM halls WHERE id = :id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Delete hall by ID (with foreign key error handling)
function deleteHall($conn, $id) {
    $query = "DELETE FROM halls WHERE id = :id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id', $id);

    try {
        $result = $stmt->execute();
        if ($result) {
            return ['success' => true, 'message' => 'Hall deleted successfully.'];
        } else {
            return ['success' => false, 'message' => 'Failed to delete hall.'];
        }
    } catch (PDOException $e) {
        $errorCode = $e->getCode();
        $errorMsg = $e->getMessage();

        if ($errorCode == 1451 || $errorCode == 19 || strpos(strtolower($errorMsg), 'foreign key constraint') !== false) {
            return ['success' => false, 'message' => 'Cannot delete hall: there are existing bookings for this hall.'];
        }

        return ['success' => false, 'message' => 'An error occurred while deleting the hall: ' . $e->getMessage()];
    }
}

// Update hall
function updateHall($conn, $id, $name, $description, $capacity, $type, $location, $price_per_hour, $amenities, $image_url = null) {
    $query = "UPDATE halls SET
                name = :name,
                description = :description,
                capacity = :capacity,
                type = :type,
                location = :location,
                price_per_hour = :price_per_hour,
                image_url = :image_url,
                amenities = :amenities
              WHERE id = :id";

    $stmt = $conn->prepare($query);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':capacity', $capacity);
    $stmt->bindParam(':type', $type);
    $stmt->bindParam(':location', $location);
    $stmt->bindParam(':price_per_hour', $price_per_hour);
    $stmt->bindParam(':image_url', $image_url);
    $stmt->bindParam(':amenities', $amenities);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);

    return $stmt->execute();
}

// Search halls with optional filters
function searchHalls($conn, $search_term, $capacity = null, $type = null) {
    $query = "SELECT * FROM halls WHERE (name LIKE :search OR location LIKE :search OR description LIKE :search)";

    if ($capacity) {
        $query .= " AND capacity >= :capacity";
    }

    if (!empty($type)) {
        $query .= " AND type = :type";
    }

    $query .= " ORDER BY name ASC";

    $stmt = $conn->prepare($query);
    $search_param = "%" . $search_term . "%";
    $stmt->bindParam(':search', $search_param);

    if ($capacity) {
        $stmt->bindParam(':capacity', $capacity);
    }

    if (!empty($type)) {
        $stmt->bindParam(':type', $type);
    }

    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>