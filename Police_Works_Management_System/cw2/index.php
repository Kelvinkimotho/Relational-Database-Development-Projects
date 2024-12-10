<?php
// index.php - Login Page
session_start();

// Check if the user is already logged in, if so, redirect to the dashboard
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Include the database connection file
    include('db.inc.php');

    $username = $_POST['username'];
    $password = $_POST['password'];

    // First, check the police_officers table
    $stmt = $conn->prepare("SELECT officer_id AS id, username, password, 'police_officer' AS role FROM police_officers WHERE username = ?");
    if ($stmt === false) {
        die('MySQL prepare error: ' . $conn->error);
    }
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    // If not found in police_officers, check the administrators table
    if (!$user) {
        $stmt = $conn->prepare("SELECT admin_id AS id, username, password, 'administrator' AS role FROM administrators WHERE username = ?");
        if ($stmt === false) {
            die('MySQL prepare error: ' . $conn->error);
        }
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
    }

    // Check if the user exists and the password is correct
    if ($user && $password === $user['password']) { 
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_role'] = $user['role']; 

        // Redirect to the dashboard
        header("Location: dashboard.php");
        exit();
    } else {
        $error = 'Invalid username or password!';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Police System</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <!-- Bootstrap CSS Link -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>
<body>

    <!-- Navbar -->
    <?php include('navbar.php'); ?>

    <!-- Login Section -->
    <div class="login-container">
        <div class="login-box">
            <h2>Login</h2>

            <!-- Error Message if login fails -->
            <?php if ($error): ?>
                <div class="error-message"><?php echo $error; ?></div>
            <?php endif; ?>

            <!-- Login Form -->
            <form action="index.php" method="POST">
                <div class="input-group">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" required>
                </div>

                <div class="input-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>
                </div>

                <button type="submit"  class="btn btn-primary">Login</button>

                <div class="links">
                    <a href="register.php">Register</a> | 
                    <a href="forgot-password.php">Forgot Password?</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Footer -->
    <?php include('footer.php'); ?>
</body>
</html>
