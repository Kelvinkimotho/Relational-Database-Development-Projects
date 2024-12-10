<?php
session_start();

// Check if the user is logged in (you may adjust this for admin/police officer checks)
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Include the database connection
include('db.inc.php');

// Handle Add Fine Operation
if (isset($_POST['add'])) {
    $incident_id = $_POST['incident_id'];
    $fine_amount = $_POST['fine_amount'];
    $issued_date = $_POST['issued_date'];

    $stmt = $conn->prepare("INSERT INTO fines (incident_id, fine_amount, issued_date) VALUES (?, ?, ?)");
    $stmt->bind_param("ids", $incident_id, $fine_amount, $issued_date);
    $stmt->execute();
    header("Location: fines.php");
}

// Handle Edit Fine Operation
if (isset($_POST['update'])) {
    $fine_id = $_POST['fine_id'];
    $incident_id = $_POST['incident_id'];
    $fine_amount = $_POST['fine_amount'];
    $issued_date = $_POST['issued_date'];

    $stmt = $conn->prepare("UPDATE fines SET incident_id = ?, fine_amount = ?, issued_date = ? WHERE fine_id = ?");
    $stmt->bind_param("idsi", $incident_id, $fine_amount, $issued_date, $fine_id);
    $stmt->execute();
    header("Location: fines.php");
}

// Handle Delete Fine Operation
if (isset($_GET['delete'])) {
    $fine_id = $_GET['delete'];

    $stmt = $conn->prepare("DELETE FROM fines WHERE fine_id = ?");
    $stmt->bind_param("i", $fine_id);
    $stmt->execute();
    header("Location: fines.php");
}

// Get search term from URL, if any
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Fetch fines with search functionality
$sql = "
    SELECT 
        f.fine_id, 
        f.fine_amount, 
        f.issued_date, 
        i.incident_id, 
        i.incident_date, 
        v.registration_number, 
        p.name AS owner_name, 
        po.name AS officer_name
    FROM fines f
    JOIN incidents i ON f.incident_id = i.incident_id
    JOIN vehicles v ON i.vehicle_id = v.vehicle_id
    JOIN people p ON v.owner_id = p.person_id
    JOIN police_officers po ON i.officer_id = po.officer_id
    WHERE i.incident_id LIKE ? OR v.registration_number LIKE ? OR p.name LIKE ? OR po.name LIKE ?
    ORDER BY f.issued_date DESC
";

// Prepare statement and bind the search term
$stmt = $conn->prepare($sql);
$search_term = "%$search%";
$stmt->bind_param("ssss", $search_term, $search_term, $search_term, $search_term);
$stmt->execute();
$result = $stmt->get_result();

// Fetch specific fine data for editing (if editing an existing fine)
if (isset($_GET['id'])) {
    $fine_id = $_GET['id'];
    $edit_sql = "SELECT * FROM fines WHERE fine_id = ?";
    $stmt = $conn->prepare($edit_sql);
    $stmt->bind_param("i", $fine_id);
    $stmt->execute();
    $edit_result = $stmt->get_result();
    $edit_fine = $edit_result->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Fines - Police System</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <!-- Bootstrap CSS Link -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>
<body>
    <?php include('navbar.php'); ?>

    <div class="content-container">
        <h2>Manage Fines</h2>

        <!-- Add or Edit Fine Form -->
        <div class="form-container">
            <h3><?php echo isset($edit_fine) ? 'Edit Fine' : 'Add New Fine'; ?></h3>
            <form method="POST">
                <label for="incident_id">Incident ID:</label>
                <select name="incident_id" id="incident_id" required>
                    <?php
                    // Fetch incidents to populate the dropdown
                    $incidents = $conn->query("SELECT incident_id, incident_date FROM incidents ORDER BY incident_date DESC");
                    while ($row = $incidents->fetch_assoc()) {
                        $selected = (isset($edit_fine) && $edit_fine['incident_id'] == $row['incident_id']) ? 'selected' : '';
                        echo "<option value='" . $row['incident_id'] . "' $selected>Incident " . $row['incident_id'] . " - " . $row['incident_date'] . "</option>";
                    }
                    ?>
                </select>

                <label for="fine_amount">Fine Amount:</label>
                <input type="number" name="fine_amount" id="fine_amount" value="<?php echo isset($edit_fine) ? $edit_fine['fine_amount'] : ''; ?>" required>

                <label for="issued_date">Issued Date:</label>
                <input type="date" name="issued_date" id="issued_date" value="<?php echo isset($edit_fine) ? $edit_fine['issued_date'] : ''; ?>" required>

                <?php if (isset($edit_fine)): ?>
                    <input type="hidden" name="fine_id" value="<?php echo $edit_fine['fine_id']; ?>">
                    <button type="submit" name="update" class="btn btn-primary">Update Fine</button>
                <?php else: ?>
                    <button type="submit" name="add" class="btn btn-primary">Add Fine</button>
                <?php endif; ?>
            </form>
        </div>

        <!-- Search Form -->
        <form method="GET" class="mb-3">
            <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" class="form-control" placeholder="Search by Incident ID, Registration Number, Owner Name, or Officer Name">
            <button type="submit" class="btn btn-primary mt-2">Search</button>
        </form>

        <!-- Display Existing Fines -->
        <h3>Existing Fines</h3>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Incident ID</th>
                    <th>Vehicle Registration</th>
                    <th>Owner Name</th>
                    <th>Police Officer</th>
                    <th>Incident Date</th>
                    <th>Fine Amount</th>
                    <th>Issued Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['incident_id']; ?></td>
                        <td><?php echo $row['registration_number']; ?></td>
                        <td><?php echo $row['owner_name']; ?></td>
                        <td><?php echo $row['officer_name']; ?></td>
                        <td><?php echo $row['incident_date']; ?></td>
                        <td><?php echo $row['fine_amount']; ?></td>
                        <td><?php echo $row['issued_date']; ?></td>
                        <td>
                            <a href="fines.php?id=<?php echo $row['fine_id']; ?>" class="btn btn-success">Edit</a>
                            <a href="?delete=<?php echo $row['fine_id']; ?>" onclick="return confirm('Are you sure you want to delete this fine?');" class="btn btn-danger">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <?php include('footer.php'); ?>
</body>
</html>
