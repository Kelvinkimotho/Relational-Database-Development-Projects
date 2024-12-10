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

// Handle Add or Update Operation
if (($is_admin) && (isset($_POST['add']) || isset($_POST['update']))) {
    $action = $_POST['action'];
    $user_type = $_SESSION['user_role']; // Assuming role is stored in session
    $user_id = $_SESSION['user_id'];
    $action_timestamp = date('Y-m-d H:i:s');

    if (isset($_POST['update'])) {
        $audit_id = $_POST['audit_id'];

        // Update audit trail entry
        $stmt = $conn->prepare("UPDATE audit_trail SET action = ?, user_type = ?, user_id = ?, action_timestamp = ? WHERE audit_id = ?");
        $stmt->bind_param("ssssi", $action, $user_type, $user_id, $action_timestamp, $audit_id);
    } else {
        // Insert new audit trail entry
        $stmt = $conn->prepare("INSERT INTO audit_trail (user_type, user_id, action, action_timestamp) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("siss", $user_type, $user_id, $action, $action_timestamp);
    }

    $stmt->execute();
    header("Location: audit_trail.php"); // Redirect back to the audit trail list
    exit();
}

// Handle Delete Operation
if ($is_admin && isset($_GET['delete'])) {
    $audit_id = $_GET['delete'];

    // Delete audit trail entry
    $stmt = $conn->prepare("DELETE FROM audit_trail WHERE audit_id = ?");
    $stmt->bind_param("i", $audit_id);
    $stmt->execute();
    header("Location: audit_trail.php"); // Redirect after delete
    exit();
}

// Get search term from URL, if any
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Fetch all audit trail entries with meaningful information and search functionality
$query = "SELECT at.audit_id, at.user_type, at.user_id, at.action, at.action_timestamp, 
                 IF(at.user_type = 'police_officer', po.name, a.name) AS user_name
          FROM audit_trail at
          LEFT JOIN police_officers po ON at.user_type = 'police_officer' AND at.user_id = po.officer_id
          LEFT JOIN administrators a ON at.user_type = 'administrator' AND at.user_id = a.admin_id
          WHERE at.action LIKE ? OR 
                IF(at.user_type = 'police_officer', po.name, a.name) LIKE ? OR 
                at.user_type LIKE ?
          ORDER BY at.action_timestamp DESC";

$stmt = $conn->prepare($query);
$search_term = "%$search%";
$stmt->bind_param("sss", $search_term, $search_term, $search_term);
$stmt->execute();
$result = $stmt->get_result();

if (!$result) {
    die("Error fetching audit trail entries: " . $conn->error);
}

// Check if we're editing an entry
if ($is_admin && isset($_GET['id'])) {
    $audit_id = $_GET['id'];
    $sql_edit = "SELECT * FROM audit_trail WHERE audit_id = $audit_id";
    $edit_result = $conn->query($sql_edit);
    $audit = $edit_result->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Audit Trail - Police System</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <!-- Bootstrap CSS Link -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>
<body>
    <?php include('navbar.php'); ?>

    <div class="content-container">
        <h2>Audit Trail</h2>

        <!-- Add or Edit Audit Trail Form (Only for Administrators) -->
        <?php if ($is_admin): ?>
            <div class="form-container">
                <h3><?php echo isset($audit) ? 'Edit Audit Trail Entry' : 'Add New Audit Trail Entry'; ?></h3>
                <form method="POST">
                    <?php if (isset($audit)): ?>
                        <input type="hidden" name="audit_id" value="<?php echo $audit['audit_id']; ?>">
                    <?php endif; ?>

                    <label for="action">Action:</label>
                    <input type="text" name="action" id="action" value="<?php echo isset($audit) ? $audit['action'] : ''; ?>" required>

                    <button type="submit" name="<?php echo isset($audit) ? 'update' : 'add'; ?>" class="btn btn-primary">
                        <?php echo isset($audit) ? 'Update Entry' : 'Add Entry'; ?>
                    </button>
                </form>
            </div>
        <?php endif; ?>

        <!-- Search Form -->
        <form method="GET" class="mb-3">
            <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" class="form-control" placeholder="Search by Action, User Name, or User Type">
            <button type="submit" class="btn btn-primary mt-2">Search</button>
        </form>

        <!-- Display Audit Trail Entries -->
        <h3>Existing Entries</h3>
        <table class="data-table">
            <thead>
                <tr>
                    <th>User Type</th>
                    <th>User Name</th>
                    <th>Action</th>
                    <th>Timestamp</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['user_type']); ?></td>
                        <td><?php echo htmlspecialchars($row['user_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['action']); ?></td>
                        <td><?php echo htmlspecialchars($row['action_timestamp']); ?></td>
                        <td>
                            <?php if ($is_admin): ?>
                                <a href="audit_trail.php?id=<?php echo $row['audit_id']; ?>" class="btn btn-success">Update</a>
                                <a href="?delete=<?php echo $row['audit_id']; ?>" onclick="return confirm('Are you sure you want to delete this entry?');" class="btn btn-danger">Delete</a>
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
