<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Police System</title>

    <!-- Link to External CSS File -->
    <link rel="stylesheet" href="../assets/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<!-- Navbar using Bootstrap 5 -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary position-sticky top-0 shadow">
  <div class="container-fluid">
    <!-- Logo Section -->
    <a class="navbar-brand text-light" href="index.php">
      <img src="../assets/img/logo.jpg" alt="" class="navbar-logo">
    </a>

    <!-- Hamburger Toggle for Mobile View -->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <!-- Navbar Links -->
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <?php if (!isset($_SESSION['user_id'])): ?>
          <li class="nav-item">
            <a class="nav-link text-dark" href="login.php">Login</a>
          </li>
        <?php else: ?>
          <li class="nav-item">
            <a class="nav-link text-dark" href="dashboard.php">Dashboard</a>
          </li>
          <li class="nav-item">
            <a class="nav-link text-dark" href="audit_trail.php">Audit Trail</a>
          </li>
          <li class="nav-item">
            <a class="nav-link text-dark" href="fines.php">Fines</a>
          </li>
          <li class="nav-item">
            <a class="nav-link text-dark" href="incidents.php">Incidents</a>
          </li>
          <li class="nav-item">
            <a class="nav-link text-dark" href="people.php">People</a>
          </li>
          <li class="nav-item">
            <a class="nav-link text-dark" href="vehicles.php">Vehicles</a>
          </li>
          <li class="nav-item">
            <a class="nav-link text-dark" href="police_officers.php">Officers</a>
          </li>
          <li class="nav-item">
            <a class="nav-link text-dark" href="administrators.php">Administrators</a>
          </li>
          <li class="nav-item">
            <a class="nav-link text-dark" href="logout.php">Logout</a>
          </li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>

<!-- Include Bootstrap JS and Popper.js for responsive navbar -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>

</body>
</html>
