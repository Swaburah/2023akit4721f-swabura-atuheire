<?php
include 'database.php';
session_start();

// Check if the user is logged in as admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Add new spot if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_spot'])) {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $location = $_POST['location'];

    // Insert new spot into the tourism_spots table
    $stmt = $pdo->prepare("INSERT INTO tourism_spots (name, description, location) VALUES (?, ?, ?)");
    $stmt->execute([$name, $description, $location]);
    $success = "New spot added successfully!";
}

// Fetch all bookings made by tourists
$stmt = $pdo->query("SELECT bookings.id, users.username, tourism_spots.name AS spot_name, bookings.booking_date 
                     FROM bookings
                     JOIN users ON bookings.user_id = users.id
                     JOIN tourism_spots ON bookings.spot_id = tourism_spots.id");
$bookings = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2>Admin Dashboard</h2>

    <!-- Add new spot form -->
    <h4>Add New Tourism Spot</h4>
    <?php if (isset($success)) { echo "<div class='alert alert-success'>$success</div>"; } ?>
    <form method="POST">
        <div class="form-group">
            <label for="name">Spot Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <textarea name="description" class="form-control" required></textarea>
        </div>
        <div class="form-group">
            <label for="location">Location</label>
            <input type="text" name="location" class="form-control" required>
        </div>
        <button type="submit" name="add_spot" class="btn btn-success">Add Spot</button>
    </form>

    <!-- Display all bookings -->
    <h4 class="mt-5">Tourist Bookings</h4>
    <table class="table mt-3">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tourist Name</th>
                <th>Spot Name</th>
                <th>Booking Date</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($bookings as $booking): ?>
                <tr>
                    <td><?= $booking['id'] ?></td>
                    <td><?= $booking['username'] ?></td>
                    <td><?= $booking['spot_name'] ?></td>
                    <td><?= $booking['booking_date'] ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>
