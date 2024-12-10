<?php
// dashboard.php - Admin dashboard page after login
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_role = $_SESSION['user_role'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Police System</title>
    <link rel="stylesheet" href="../assets/css/style.css"> <!-- Link to the external CSS file -->
    <!-- Bootstrap CSS Link -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>
<body class="dashboard-page">
    <?php include('navbar.php'); ?>
    
    <div class="dashboard-container">
        <div class="dashboard-box">
            <h2>Welcome to the Dashboard</h2>
            <p>Hello, <?php echo $_SESSION['user_role']; ?>!</p>

            <div class="dashboard-content">
                <h3>Manage the system</h3>
                <ul>
                    <?php if ($user_role == 'admin'): ?>
                        <li><a href="manage-users.php">Manage Users</a></li>
                        <li><a href="audit-trail.php">View Audit Trail</a></li>
                        <li><a href="fines.php">View Fines</a></li>
                    <?php elseif ($user_role == 'officer'): ?>
                        <li><a href="incidents.php">Manage Incidents</a></li>
                        <li><a href="vehicles.php">Manage Vehicles</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </div>

    <?php include('footer.php'); ?>
</body>
</html>

