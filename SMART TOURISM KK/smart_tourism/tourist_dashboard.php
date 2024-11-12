<?php
include 'database.php';
session_start();

// Check if the user is logged in as tourist
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'tourist') {
    header("Location: login.php");
    exit;
}

// Fetch all available spots
$stmt = $pdo->query("SELECT * FROM tourism_spots");
$spots = $stmt->fetchAll();

// Handle booking submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['book_spot'])) {
    $spot_id = $_POST['spot_id'];
    $booking_date = date('Y-m-d'); // Current date as booking date

    // Insert booking into the bookings table
    $stmt = $pdo->prepare("INSERT INTO bookings (user_id, spot_id, booking_date) VALUES (?, ?, ?)");
    $stmt->execute([$_SESSION['user_id'], $spot_id, $booking_date]);
    $success = "Booking successful!";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tourist Dashboard</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Tourist Dashboard</h2>
        <a href="logout.php" class="btn btn-danger">Logout</a> <!-- Logout link -->
    </div>

    <!-- Display success message -->
    <?php if (isset($success)) { echo "<div class='alert alert-success'>$success</div>"; } ?>

    <h4>Available Spots</h4>
    <table class="table mt-3">
        <thead>
            <tr>
                <th>ID</th>
                <th>Spot Name</th>
                <th>Description</th>
                <th>Location</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($spots as $spot): ?>
                <tr>
                    <td><?= $spot['id'] ?></td>
                    <td><?= $spot['name'] ?></td>
                    <td><?= $spot['description'] ?></td>
                    <td><?= $spot['location'] ?></td>
                    <td>
                        <!-- Booking form for each spot -->
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="spot_id" value="<?= $spot['id'] ?>">
                            <button type="submit" name="book_spot" class="btn btn-primary">Book</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>
