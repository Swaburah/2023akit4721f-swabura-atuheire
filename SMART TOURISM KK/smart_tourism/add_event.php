<?php
include 'database.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $spot_id = $_POST['spot_id'];
    $event_name = $_POST['event_name'];
    $event_date = $_POST['event_date'];
    $description = $_POST['description'];

    $stmt = $pdo->prepare("INSERT INTO events (spot_id, event_name, event_date, description) VALUES (?, ?, ?, ?)");
    $stmt->execute([$spot_id, $event_name, $event_date, $description]);

    header("Location: admin_dashboard.php");
    exit;
}

$spots = $pdo->query("SELECT * FROM tourism_spots")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Add Event</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2>Add Event</h2>
    <form method="POST">
        <div class="form-group">
            <label for="spot_id">Tourism Spot</label>
            <select name="spot_id" class="form-control" required>
                <?php foreach ($spots as $spot): ?>
                    <option value="<?= $spot['id'] ?>"><?= $spot['name'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="event_name">Event Name</label>
            <input type="text" name="event_name" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="event_date">Event Date</label>
            <input type="datetime-local" name="event_date" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <textarea name="description" class="form-control" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Add Event</button>
    </form>
</div>
</body>
</html>
