<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Include the database connection
include('db.inc.php');

// Handle Add Officer Operation
if (isset($_POST['add'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $name = $_POST['name'];

    // Password hashing
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO police_officers (username, password, name) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $hashed_password, $name);
    $stmt->execute();
    header("Location: police_officers.php");
}

// Handle Edit Officer Operation
if (isset($_POST['update'])) {
    $officer_id = $_POST['officer_id'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $name = $_POST['name'];

    // If password is provided, hash it
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("UPDATE police_officers SET username = ?, password = ?, name = ? WHERE officer_id = ?");
    $stmt->bind_param("sssi", $username, $hashed_password, $name, $officer_id);
    $stmt->execute();
    header("Location: police_officers.php");
}

// Handle Delete Officer Operation
if (isset($_GET['delete'])) {
    $officer_id = $_GET['delete'];

    $stmt = $conn->prepare("DELETE FROM police_officers WHERE officer_id = ?");
    $stmt->bind_param("i", $officer_id);
    $stmt->execute();
    header("Location: police_officers.php");
}

// Fetch all police officers with search functionality
$search = isset($_GET['search']) ? $_GET['search'] : '';  // Use GET to fetch the search term

$sql = "SELECT * FROM police_officers WHERE username LIKE ? OR name LIKE ?";
$stmt = $conn->prepare($sql);
$search_term = "%$search%";  // Add wildcard for partial match
$stmt->bind_param("ss", $search_term, $search_term);
$stmt->execute();
$result = $stmt->get_result();

// Check if we're editing an officer
if (isset($_GET['id'])) {
    $officer_id = $_GET['id'];
    $sql_edit = "SELECT * FROM police_officers WHERE officer_id = $officer_id";
    $edit_result = $conn->query($sql_edit);
    $officer = $edit_result->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Police Officers - Police System</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <!-- Bootstrap CSS Link -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>
<body>
    <?php include('navbar.php'); ?>

    <div class="content-container">
        <h2>Manage Police Officers</h2>

        <!-- Add or Edit Officer Form -->
        <div class="form-container">
            <h3><?php echo isset($officer) ? 'Edit Officer' : 'Add New Officer'; ?></h3>
            <form method="POST">
                <?php if (isset($officer)): ?>
                    <input type="hidden" name="officer_id" value="<?php echo $officer['officer_id']; ?>">
                <?php endif; ?>

                <label for="username">Username:</label>
                <input type="text" name="username" value="<?php echo isset($officer) ? $officer['username'] : ''; ?>" required>

                <label for="password">Password:</label>
                <input type="password" name="password" value="<?php echo isset($officer) ? '' : ''; ?>" required>

                <label for="name">Name:</label>
                <input type="text" name="name" value="<?php echo isset($officer) ? $officer['name'] : ''; ?>" required>

                <button type="submit" name="<?php echo isset($officer) ? 'update' : 'add'; ?>" class="btn btn-primary">
                    <?php echo isset($officer) ? 'Update Officer' : 'Add Officer'; ?>
                </button>
            </form>
        </div>

        <!-- Search Form -->
        <form method="GET" class="mb-3">
            <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" class="form-control" placeholder="Search by username or name">
            <button type="submit" class="btn btn-primary mt-2">Search</button>
        </form>

        <!-- Display Existing Police Officers -->
        <h3>Existing Officers</h3>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Officer ID</th>
                    <th>Username</th>
                    <th>Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['officer_id']; ?></td>
                        <td><?php echo $row['username']; ?></td>
                        <td><?php echo $row['name']; ?></td>
                        <td>
                            <a href="police_officers.php?id=<?php echo $row['officer_id']; ?>" class="btn btn-success">Update</a>
                            <a href="?delete=<?php echo $row['officer_id']; ?>" onclick="return confirm('Are you sure you want to delete this officer?');" class="btn btn-danger">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <?php include('footer.php'); ?>
</body>
</html>
