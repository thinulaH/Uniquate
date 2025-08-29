<?php 
require_once __DIR__."/includes/config.php"; 

// Build base query
$sql = "SELECT * FROM rooms WHERE available=1";
$params = [];
$types  = "";

// Apply filters
if(!empty($_GET['capacity'])){
    $sql .= " AND capacity >= ?";
    $params[] = (int)$_GET['capacity'];
    $types .= "i";
}
if(!empty($_GET['type'])){
    $sql .= " AND type = ?";
    $params[] = $_GET['type'];
    $types .= "s";
}

// Extend filter: Exclude rooms already booked for requested date and time
if(!empty($_GET['date']) && !empty($_GET['time'])){
    $sql .= " AND id NOT IN (
        SELECT room_id FROM bookings WHERE date = ? AND time = ?
    )";
    $params[] = $_GET['date'];
    $params[] = $_GET['time'];
    $types .= "ss";
}

$sql .= " ORDER BY id DESC";

// Prepare + bind if filters exist
$stmt = $conn->prepare($sql);
if(!empty($params)){
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$rooms = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Uniquate</title>
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<header style="display: flex; justify-content: space-between; align-items: center; padding: 10px; border-bottom: 1px solid #ddd;">
  <div>
    <img src="logo.png" alt="Uniquate Logo" style="height: 50px;">
  </div>
  <nav>
    <a href="register.php" style="margin-right: 15px;">Register</a>
    <a href="login.php">Login</a>
  </nav>
</header>

<section style="text-align: center; margin: 20px;">
  <h1>Find Your Perfect Hall</h1>
  <form method="GET" action="index.php" style="margin: 20px auto; display: flex; flex-wrap: wrap; justify-content: center; gap: 15px;">
    <input type="date" name="date" value="<?php echo e($_GET['date'] ?? ''); ?>">
    <input type="time" name="time" value="<?php echo e($_GET['time'] ?? ''); ?>">
    <input type="number" name="capacity" placeholder="Capacity" min="1" value="<?php echo e($_GET['capacity'] ?? ''); ?>">
    <select name="type">
      <option value="">Select Hall Type</option>
      <option value="lecture" <?php if(($_GET['type'] ?? '')==='lecture') echo 'selected'; ?>>Lecture Hall</option>
      <option value="lab" <?php if(($_GET['type'] ?? '')==='lab') echo 'selected'; ?>>Laboratory</option>
      <option value="auditorium" <?php if(($_GET['type'] ?? '')==='auditorium') echo 'selected'; ?>>Auditorium</option>
      <option value="meeting" <?php if(($_GET['type'] ?? '')==='meeting') echo 'selected'; ?>>Meeting Room</option>
    </select>
    <button type="submit">Search</button>
  </form>
</section>

<section style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; padding: 20px;">
  <?php if($rooms->num_rows > 0): ?>
    <?php while($r = $rooms->fetch_assoc()): ?>
      <div>
        <img src="uploads/<?php echo e($r['image']); ?>" alt="<?php echo e($r['name']); ?>" style="width:100%; height:200px; object-fit:cover;">
        <h3><?php echo e($r['name']); ?></h3>
        <p><?php echo e($r['description']); ?></p>
        <p><strong>Capacity:</strong> <?php echo (int)$r['capacity']; ?></p>
        <p><strong>Location:</strong> <?php echo e($r['location']); ?></p>
      </div>
    <?php endwhile; ?>
  <?php else: ?>
    <p>No halls found matching your criteria.</p>
  <?php endif; ?>
</section>

</body>
</html>