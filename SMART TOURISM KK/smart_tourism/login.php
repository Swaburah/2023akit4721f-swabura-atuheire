<?php
include 'database.php';
session_start();

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepare and execute the query
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    // Debugging: Check user data
    var_dump($user); // To check if user is being fetched properly

    // Check if user exists and if password is correct
    if ($user && password_verify($password, $user['password'])) {
        // Store user information in session variables
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];

        

        // Redirect to the appropriate dashboard based on role
        if ($user['role'] == 'admin') {
            header("Location: admin_dashboard.php"); // Admin dashboard
            exit;
        } else {
            header("Location: tourist_dashboard.php"); // Tourist dashboard
            exit;
        }
    } else {
        // If login fails, show an error message
        $error = "Invalid email or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2>Login</h2>
    
    <!-- Display error message if login failed -->
    <?php if (isset($error)) { echo "<div class='alert alert-danger'>$error</div>"; } ?>
    
    <!-- Login form -->
    <form method="POST">
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Login</button>
    </form>
</div>
</body>
</html>
