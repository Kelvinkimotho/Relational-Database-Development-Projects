<?php
// login.php - Login Page
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

    // Check the police_officers table
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
    if ($user && $password === $user['password']) { // For plain text passwords
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_role'] = $user['role']; // 'police_officer' or 'administrator'

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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous"></head>
<body>
    <?php include('navbar.php'); ?>
    
    <div class="form-container">
        <div >
            <h2>Login</h2>

            <?php if ($error): ?>
                <div class="error-message"><?php echo $error; ?></div>
            <?php endif; ?>

            <form action="login.php" method="POST">
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
    
    <?php include('footer.php'); ?>
</body>
</html>
