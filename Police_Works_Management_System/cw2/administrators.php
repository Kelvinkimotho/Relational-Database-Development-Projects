<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Include the database connection
include('db.inc.php');

// Restrict add, update, and delete actions to administrators only
$is_admin = isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'administrator';

// Handle Add Administrator Operation
if ($is_admin && isset($_POST['add'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $name = $_POST['name'];

    // Password hashing
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO administrators (username, password, name) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $hashed_password, $name);
    $stmt->execute();
    header("Location: administrators.php");
    exit();
}

// Handle Edit Administrator Operation
if ($is_admin && isset($_POST['update'])) {
    $admin_id = $_POST['admin_id'];
    $username = $_POST['username'];
    $name = $_POST['name'];

    // Update query for administrator
    $stmt = $conn->prepare("UPDATE administrators SET username = ?, name = ? WHERE admin_id = ?");
    $stmt->bind_param("ssi", $username, $name, $admin_id);
    $stmt->execute();
    header("Location: administrators.php");
    exit();
}

// Handle Delete Administrator Operation
if ($is_admin && isset($_GET['delete'])) {
    $admin_id = $_GET['delete'];

    $stmt = $conn->prepare("DELETE FROM administrators WHERE admin_id = ?");
    $stmt->bind_param("i", $admin_id);
    $stmt->execute();
    header("Location: administrators.php");
    exit();
}

// Fetch all administrators with search functionality
$search = isset($_GET['search']) ? $_GET['search'] : '';  // Use GET to fetch the search term

// SQL query with search functionality
$sql = "SELECT * FROM administrators WHERE username LIKE ? OR name LIKE ?";
$stmt = $conn->prepare($sql);
$search_term = "%$search%";  // Add wildcard for partial match
$stmt->bind_param("ss", $search_term, $search_term);
$stmt->execute();
$result = $stmt->get_result();

// Check if we're editing an administrator
if ($is_admin && isset($_GET['id'])) {
    $admin_id = $_GET['id'];
    $sql_edit = "SELECT * FROM administrators WHERE admin_id = $admin_id";
    $edit_result = $conn->query($sql_edit);
    $admin = $edit_result->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Administrators - Police System</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <!-- Bootstrap CSS Link -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>
<body>
    <?php include('navbar.php'); ?>

    <div class="content-container">
        <h2>Manage Administrators</h2>

        <!-- Add or Edit Administrator Form (Only for Administrators) -->
        <?php if ($is_admin): ?>
            <div class="form-container">
                <h3><?php echo isset($admin) ? 'Edit Administrator' : 'Add New Administrator'; ?></h3>
                <form method="POST">
                    <input type="hidden" name="admin_id" value="<?php echo isset($admin) ? $admin['admin_id'] : ''; ?>">

                    <label for="username">Username:</label>
                    <input type="text" name="username" value="<?php echo isset($admin) ? $admin['username'] : ''; ?>" required>

                    <label for="password">Password:</label>
                    <input type="password" name="password" <?php echo isset($admin) ? '' : 'required'; ?>>

                    <label for="name">Name:</label>
                    <input type="text" name="name" value="<?php echo isset($admin) ? $admin['name'] : ''; ?>" required>

                    <button type="submit" name="<?php echo isset($admin) ? 'update' : 'add'; ?>" class="btn btn-primary">
                        <?php echo isset($admin) ? 'Update Administrator' : 'Add Administrator'; ?>
                    </button>
                </form>
            </div>
        <?php endif; ?>

        <!-- Search Form -->
        <form method="GET" class="mb-3">
            <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" class="form-control" placeholder="Search by username or name">
            <button type="submit" class="btn btn-primary mt-2">Search</button>
        </form>

        <!-- Display Existing Administrators -->
        <h3>Existing Administrators</h3>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Admin ID</th>
                    <th>Username</th>
                    <th>Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['admin_id']; ?></td>
                        <td><?php echo htmlspecialchars($row['username']); ?></td>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td>
                            <?php if ($is_admin): ?>
                                <a href="administrators.php?id=<?php echo $row['admin_id']; ?>" class="btn btn-success">Update</a>
                                <a href="?delete=<?php echo $row['admin_id']; ?>" onclick="return confirm('Are you sure you want to delete this administrator?');" class="btn btn-danger">Delete</a>
                            <?php else: ?>
                                <span class="text-danger">Restricted</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <?php include('footer.php'); ?>
</body>
</html>
