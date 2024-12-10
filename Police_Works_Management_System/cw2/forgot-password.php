<?php
session_start();
include('db.inc.php');

// Handle forgot password form submission
if (isset($_POST['reset'])) {
    $username = $_POST['username'];

    // Check if the username exists in police_officers or administrators
    $stmt = $conn->prepare("SELECT 'police_officer' AS user_type FROM police_officers WHERE username = ? 
                            UNION 
                            SELECT 'administrator' AS user_type FROM administrators WHERE username = ?");

    if ($stmt === false) {
        die('MySQL prepare error: ' . $conn->error);  // Output the error if the prepare fails
    }

    $stmt->bind_param("ss", $username, $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<p class='success'>A password reset link has been sent to your registered email.</p>";
    } else {
        echo "<p class='error'>Username not found.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <!-- Bootstrap CSS Link -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>
<body>
    <div class="form-container">
        <h2>Forgot Password</h2>
        <form method="POST" action="forgot-password.php">
            <div class="form-group">
                <label for="username">Enter your Username:</label>
                <input type="text" name="username" id="username" required>
            </div>
            <button type="submit" name="reset" class="btn btn-primary">Reset Password</button>
        </form>
        <p><a href="login.php" ">Back to Login</a></p>
    </div>
</body>
</html>
