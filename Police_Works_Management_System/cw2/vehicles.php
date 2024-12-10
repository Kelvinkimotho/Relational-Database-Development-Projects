<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Include the database connection
include('db.inc.php');

// Handle Add Vehicle Operation
if (isset($_POST['add'])) {
    $registration_number = $_POST['registration_number'];
    $make = $_POST['make'];
    $model = $_POST['model'];
    $color = $_POST['color'];
    $owner_id = $_POST['owner_id'];

    $stmt = $conn->prepare("INSERT INTO vehicles (registration_number, make, model, color, owner_id) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssi", $registration_number, $make, $model, $color, $owner_id);
    $stmt->execute();
    header("Location: vehicles.php");
}

// Handle Edit Vehicle Operation
if (isset($_POST['update'])) {
    $vehicle_id = $_POST['vehicle_id'];
    $registration_number = $_POST['registration_number'];
    $make = $_POST['make'];
    $model = $_POST['model'];
    $color = $_POST['color'];
    $owner_id = $_POST['owner_id'];

    $stmt = $conn->prepare("UPDATE vehicles SET registration_number = ?, make = ?, model = ?, color = ?, owner_id = ? WHERE vehicle_id = ?");
    $stmt->bind_param("ssssii", $registration_number, $make, $model, $color, $owner_id, $vehicle_id);
    $stmt->execute();
    header("Location: vehicles.php");
}

// Handle Delete Vehicle Operation
if (isset($_GET['delete'])) {
    $vehicle_id = $_GET['delete'];

    $stmt = $conn->prepare("DELETE FROM vehicles WHERE vehicle_id = ?");
    $stmt->bind_param("i", $vehicle_id);
    $stmt->execute();
    header("Location: vehicles.php");
}

// Handle Search Operation
$search_query = '';
if (isset($_GET['search'])) {
    $search_query = $_GET['search'];
    $sql = "SELECT v.vehicle_id, v.registration_number, v.make, v.model, v.color, p.name AS owner_name
            FROM vehicles v
            LEFT JOIN people p ON v.owner_id = p.person_id
            WHERE v.registration_number LIKE ?";
    $stmt = $conn->prepare($sql);
    $search_param = "%" . $search_query . "%";
    $stmt->bind_param("s", $search_param);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    // Default query to fetch all vehicles
    $sql = "SELECT v.vehicle_id, v.registration_number, v.make, v.model, v.color, p.name AS owner_name
            FROM vehicles v
            LEFT JOIN people p ON v.owner_id = p.person_id";
    $result = $conn->query($sql);
}

// Fetch people for dropdown (for both adding and editing)
$people = $conn->query("SELECT person_id, name FROM people");

// Check if we're editing a vehicle
if (isset($_GET['id'])) {
    $vehicle_id = $_GET['id'];
    $sql_edit = "SELECT * FROM vehicles WHERE vehicle_id = $vehicle_id";
    $edit_result = $conn->query($sql_edit);
    $vehicle = $edit_result->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Vehicles - Police System</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <!-- Bootstrap CSS Link -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>
<body>
    <?php include('navbar.php'); ?>

    <div class="content-container">
        <h2>Manage Vehicles</h2>

        <!-- Add or Edit Vehicle Form -->
        <div class="form-container">
            <h3><?php echo isset($vehicle) ? 'Edit Vehicle' : 'Add New Vehicle'; ?></h3>
            <form method="POST">
                <input type="hidden" name="vehicle_id" value="<?php echo isset($vehicle) ? $vehicle['vehicle_id'] : ''; ?>">

                <label for="registration_number">Registration Number:</label>
                <input type="text" name="registration_number" value="<?php echo isset($vehicle) ? $vehicle['registration_number'] : ''; ?>" required>

                <label for="make">Make:</label>
                <input type="text" name="make" value="<?php echo isset($vehicle) ? $vehicle['make'] : ''; ?>" required>

                <label for="model">Model:</label>
                <input type="text" name="model" value="<?php echo isset($vehicle) ? $vehicle['model'] : ''; ?>" required>

                <label for="color">Color:</label>
                <input type="text" name="color" value="<?php echo isset($vehicle) ? $vehicle['color'] : ''; ?>" required>

                <label for="owner_id">Owner:</label>
                <select name="owner_id" required>
                    <?php while ($person = $people->fetch_assoc()): ?>
                        <option value="<?php echo $person['person_id']; ?>"
                            <?php echo isset($vehicle) && $vehicle['owner_id'] == $person['person_id'] ? 'selected' : ''; ?>>
                            <?php echo $person['name']; ?>
                        </option>
                    <?php endwhile; ?>
                </select>

                <button type="submit" name="<?php echo isset($vehicle) ? 'update' : 'add'; ?>" class="btn btn-primary">
                    <?php echo isset($vehicle) ? 'Update Vehicle' : 'Add Vehicle'; ?>
                </button>
            </form>
        </div>

        <!-- Display Existing Vehicles -->
        <h3>Existing Vehicles</h3>
        <!-- Search Form -->
        <form method="GET" class="mb-3">
            <input type="text" name="search" value="<?php echo htmlspecialchars($search_query); ?>" class="form-control" placeholder="Search by Registration Number" />
            <button type="submit" class="btn btn-primary mt-2">Search</button>
        </form>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Vehicle ID</th>
                    <th>Registration Number</th>
                    <th>Make</th>
                    <th>Model</th>
                    <th>Color</th>
                    <th>Owner</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['vehicle_id']; ?></td>
                            <td><?php echo $row['registration_number']; ?></td>
                            <td><?php echo $row['make']; ?></td>
                            <td><?php echo $row['model']; ?></td>
                            <td><?php echo $row['color']; ?></td>
                            <td><?php echo $row['owner_name'] ? $row['owner_name'] : 'Unknown'; ?></td>
                            <td>
                                <a href="vehicles.php?id=<?php echo $row['vehicle_id']; ?>" class="btn btn-success">Update</a>
                                <a href="?delete=<?php echo $row['vehicle_id']; ?>" onclick="return confirm('Are you sure you want to delete this vehicle?');" class="btn btn-danger">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7">No vehicles found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php include('footer.php'); ?>
</body>
</html>
