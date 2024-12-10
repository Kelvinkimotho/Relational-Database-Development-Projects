<?php
session_start();
include('db.inc.php');

// Check if the user is logged in and if they are an administrator
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'administrator') {
    // Redirect to login page if the user is not an admin
    header("Location: login.php");
    exit("Access denied. Only administrators can register users.");
}

// Handle registration form submission
if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $password = $_POST['password']; // No hashing
    $name = $_POST['name'];
    $user_type = $_POST['user_type']; // 'police_officer' or 'administrator'

    // Ensure the user type is valid before proceeding
    if ($user_type === 'police_officer') {
        $stmt = $conn->prepare("INSERT INTO police_officers (username, password, name) VALUES (?, ?, ?)");
    } else if ($user_type === 'administrator') {
        $stmt = $conn->prepare("INSERT INTO administrators (username, password, name) VALUES (?, ?, ?)");
    } else {
        echo "<p class='error'>Invalid user type selected.</p>";
        exit();
    }

    // Bind parameters and execute the query
    $stmt->bind_param("sss", $username, $password, $name);

    if ($stmt->execute()) {
        echo "<p class='success'>Registration successful! <a href='login.php'>Login here</a>.</p>";
    } else {
        echo "<p class='error'>Error: " . $stmt->error . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <!-- Bootstrap CSS Link -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>
<body>
    <div class="form-container">
        <h2>Register New User</h2>
        <form method="POST" action="register.php">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" name="username" id="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" name="password" id="password" required>
            </div>
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" name="name" id="name" required>
            </div>
            <div class="form-group">
                <label for="user_type">User Type:</label>
                <select name="user_type" id="user_type" required>
                    <option value="police_officer">Police Officer</option>
                    <option value="administrator">Administrator</option>
                </select>
            </div>
            <button type="submit" name="register" class="btn btn-primary">Register</button>
        </form>
        <p>Already have an account? <a href="login.php">Login here</a>.</p>
    </div>
</body>
</html>
