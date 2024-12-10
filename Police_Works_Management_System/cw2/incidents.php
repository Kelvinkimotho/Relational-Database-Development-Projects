<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Include the database connection
include('db.inc.php');

// Handle Add Incident Operation
if (isset($_POST['add'])) {
    $incident_date = $_POST['incident_date'];
    $description = $_POST['description'];
    $officer_id = $_POST['officer_id'];
    $vehicle_id = $_POST['vehicle_id'];
    $person_id = $_POST['person_id'];

    $stmt = $conn->prepare("INSERT INTO incidents (incident_date, description, officer_id, vehicle_id, person_id) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssiii", $incident_date, $description, $officer_id, $vehicle_id, $person_id);
    $stmt->execute();
    header("Location: incidents.php");
}

// Handle Edit Incident Operation
if (isset($_POST['update'])) {
    $incident_id = $_POST['incident_id'];
    $incident_date = $_POST['incident_date'];
    $description = $_POST['description'];
    $officer_id = $_POST['officer_id'];
    $vehicle_id = $_POST['vehicle_id'];
    $person_id = $_POST['person_id'];

    $stmt = $conn->prepare("UPDATE incidents SET incident_date = ?, description = ?, officer_id = ?, vehicle_id = ?, person_id = ? WHERE incident_id = ?");
    $stmt->bind_param("ssiiii", $incident_date, $description, $officer_id, $vehicle_id, $person_id, $incident_id);
    $stmt->execute();
    header("Location: incidents.php");
}

// Handle Delete Incident Operation
if (isset($_GET['delete'])) {
    $incident_id = $_GET['delete'];

    $stmt = $conn->prepare("DELETE FROM incidents WHERE incident_id = ?");
    $stmt->bind_param("i", $incident_id);
    $stmt->execute();
    header("Location: incidents.php");
}

// Handle Search Operation
$search_query = '';
if (isset($_GET['search'])) {
    $search_query = $_GET['search'];
    $sql = "SELECT i.incident_id, i.incident_date, i.description, o.name AS officer_name, v.registration_number AS vehicle, p.name AS person 
            FROM incidents i
            JOIN police_officers o ON i.officer_id = o.officer_id
            JOIN vehicles v ON i.vehicle_id = v.vehicle_id
            JOIN people p ON i.person_id = p.person_id
            WHERE i.description LIKE ? OR o.name LIKE ? OR v.registration_number LIKE ? OR p.name LIKE ?";
    $stmt = $conn->prepare($sql);
    $search_param = "%" . $search_query . "%";
    $stmt->bind_param("ssss", $search_param, $search_param, $search_param, $search_param);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    // Default query to fetch all incidents
    $sql = "SELECT i.incident_id, i.incident_date, i.description, o.name AS officer_name, v.registration_number AS vehicle, p.name AS person 
            FROM incidents i
            JOIN police_officers o ON i.officer_id = o.officer_id
            JOIN vehicles v ON i.vehicle_id = v.vehicle_id
            JOIN people p ON i.person_id = p.person_id";
    $result = $conn->query($sql);
}

// Check if we're editing an incident
if (isset($_GET['id'])) {
    $incident_id = $_GET['id'];
    $sql_edit = "SELECT * FROM incidents WHERE incident_id = $incident_id";
    $edit_result = $conn->query($sql_edit);
    $incident = $edit_result->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Incidents - Police System</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <!-- Bootstrap CSS Link -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>
<body>
    <?php include('navbar.php'); ?>

    <div class="content-container">
        <h2>Manage Incidents</h2>

        <!-- Add or Edit Incident Form -->
        <div class="form-container">
            <h3><?php echo isset($incident) ? 'Edit Incident' : 'Add New Incident'; ?></h3>
            <form method="POST">
                <?php if (isset($incident)): ?>
                    <input type="hidden" name="incident_id" value="<?php echo $incident['incident_id']; ?>">
                <?php endif; ?>

                <label for="incident_date">Incident Date:</label>
                <input type="datetime-local" name="incident_date" value="<?php echo isset($incident) ? $incident['incident_date'] : ''; ?>" required>

                <label for="description">Description:</label>
                <textarea name="description" required><?php echo isset($incident) ? $incident['description'] : ''; ?></textarea>

                <label for="officer_id">Officer:</label>
                <select name="officer_id" required>
                    <?php
                    // Fetch all officers
                    $officers = $conn->query("SELECT officer_id, name FROM police_officers");
                    while ($officer = $officers->fetch_assoc()) {
                        echo "<option value='" . $officer['officer_id'] . "' " . (isset($incident) && $incident['officer_id'] == $officer['officer_id'] ? 'selected' : '') . ">" . $officer['name'] . "</option>";
                    }
                    ?>
                </select>

                <label for="vehicle_id">Vehicle:</label>
                <select name="vehicle_id" required>
                    <?php
                    // Fetch all vehicles
                    $vehicles = $conn->query("SELECT vehicle_id, registration_number FROM vehicles");
                    while ($vehicle = $vehicles->fetch_assoc()) {
                        echo "<option value='" . $vehicle['vehicle_id'] . "' " . (isset($incident) && $incident['vehicle_id'] == $vehicle['vehicle_id'] ? 'selected' : '') . ">" . $vehicle['registration_number'] . "</option>";
                    }
                    ?>
                </select>

                <label for="person_id">Person:</label>
                <select name="person_id" required>
                    <?php
                    // Fetch all people
                    $people = $conn->query("SELECT person_id, name FROM people");
                    while ($person = $people->fetch_assoc()) {
                        echo "<option value='" . $person['person_id'] . "' " . (isset($incident) && $incident['person_id'] == $person['person_id'] ? 'selected' : '') . ">" . $person['name'] . "</option>";
                    }
                    ?>
                </select>

                <button type="submit" name="<?php echo isset($incident) ? 'update' : 'add'; ?>" class="btn btn-primary">
                    <?php echo isset($incident) ? 'Update Incident' : 'Add Incident'; ?>
                </button>
            </form>
        </div>

        <!-- Display Existing Incidents -->
        <h3>Existing Incidents</h3>
        <!-- Search Form -->
        <form method="GET" class="mb-3">
            <input type="text" name="search" value="<?php echo htmlspecialchars($search_query); ?>" class="form-control" placeholder="Search by Description, Officer, Vehicle, or Person" />
            <button type="submit" class="btn btn-primary mt-2">Search</button>
        </form>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Incident ID</th>
                    <th>Incident Date</th>
                    <th>Description</th>
                    <th>Officer</th>
                    <th>Vehicle</th>
                    <th>Person</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['incident_id']; ?></td>
                        <td><?php echo $row['incident_date']; ?></td>
                        <td><?php echo $row['description']; ?></td>
                        <td><?php echo $row['officer_name']; ?></td>
                        <td><?php echo $row['vehicle']; ?></td>
                        <td><?php echo $row['person']; ?></td>
                        <td>
                            <a href="incidents.php?id=<?php echo $row['incident_id']; ?>" class="btn btn-success">Update</a>
                            <a href="?delete=<?php echo $row['incident_id']; ?>" onclick="return confirm('Are you sure you want to delete this incident?');" class="btn btn-danger">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <?php include('footer.php'); ?>
</body>
</html>
