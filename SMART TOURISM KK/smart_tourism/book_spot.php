<?php
include 'database.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$spot_id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM tourism_spots WHERE id = ?");
$stmt->execute([$spot_id]);
$spot = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $spot_id = $_POST['spot_id'];

    $stmt = $pdo->prepare("INSERT INTO bookings (user_id, spot_id) VALUES (?, ?)");
    $stmt->execute([$user_id, $spot_id]);

    $success = "Booking successful!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Book Spot</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2>Book a Spot: <?= $spot['name'] ?></h2>
    <?php if (isset($success)) { echo "<div class='alert alert-success'>$success</div>"; } ?>
    <form method="POST">
        <input type="hidden" name="spot_id" value="<?= $spot['id'] ?>">
        <button type="submit" class="btn btn-primary">Book Now</button>
    </form>
</div>
</body>
</html>
