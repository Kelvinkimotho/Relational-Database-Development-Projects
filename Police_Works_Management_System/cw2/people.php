<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Include the database connection
include('db.inc.php');

// Handle Add Person Operation
if (isset($_POST['add'])) {
    $name = $_POST['name'];
    $license_number = $_POST['license_number'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];

    $stmt = $conn->prepare("INSERT INTO people (name, license_number, address, phone) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $license_number, $address, $phone);
    $stmt->execute();
    header("Location: people.php");
}

// Handle Edit Person Operation
if (isset($_POST['update'])) {
    $person_id = $_POST['person_id'];
    $name = $_POST['name'];
    $license_number = $_POST['license_number'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];

    $stmt = $conn->prepare("UPDATE people SET name = ?, license_number = ?, address = ?, phone = ? WHERE person_id = ?");
    $stmt->bind_param("ssssi", $name, $license_number, $address, $phone, $person_id);
    $stmt->execute();
    header("Location: people.php");
}

// Handle Delete Person Operation
if (isset($_GET['delete'])) {
    $person_id = $_GET['delete'];

    $stmt = $conn->prepare("DELETE FROM people WHERE person_id = ?");
    $stmt->bind_param("i", $person_id);
    $stmt->execute();
    header("Location: people.php");
}

// Search functionality
$search = '';
if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $sql = "SELECT * FROM people WHERE name LIKE ? OR license_number LIKE ? OR address LIKE ? OR phone LIKE ?";
    $stmt = $conn->prepare($sql);
    $search_term = "%$search%";
    $stmt->bind_param("ssss", $search_term, $search_term, $search_term, $search_term);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    // Fetch all people if no search query
    $sql = "SELECT * FROM people";
    $result = $conn->query($sql);
}

// Check if we're editing a person
if (isset($_GET['id'])) {
    $person_id = $_GET['id'];
    $sql_edit = "SELECT * FROM people WHERE person_id = $person_id";
    $edit_result = $conn->query($sql_edit);
    $person = $edit_result->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage People - Police System</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <!-- Bootstrap CSS Link -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>
<body>
    <?php include('navbar.php'); ?>

    <div class="content-container">
        <h2>Manage People</h2>

        <!-- Add or Edit Person Form -->
        <div class="form-container">
            <h3><?php echo isset($person) ? 'Edit Person' : 'Add New Person'; ?></h3>
            <form method="POST">
                <?php if (isset($person)): ?>
                    <input type="hidden" name="person_id" value="<?php echo $person['person_id']; ?>">
                <?php endif; ?>

                <label for="name">Name:</label>
                <input type="text" name="name" value="<?php echo isset($person) ? $person['name'] : ''; ?>" required>

                <label for="license_number">License Number:</label>
                <input type="text" name="license_number" value="<?php echo isset($person) ? $person['license_number'] : ''; ?>" required>

                <label for="address">Address:</label>
                <textarea name="address" required><?php echo isset($person) ? $person['address'] : ''; ?></textarea>

                <label for="phone">Phone:</label>
                <input type="text" name="phone" value="<?php echo isset($person) ? $person['phone'] : ''; ?>" required>

                <button type="submit" name="<?php echo isset($person) ? 'update' : 'add'; ?>"  class="btn btn-primary">
                    <?php echo isset($person) ? 'Update Person' : 'Add Person'; ?>
                </button>
            </form>
        </div>

        <!-- Display Existing People -->
        <h3>Existing People</h3>
        <!-- Search Form -->
        <form method="GET" class="mb-3">
            <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" class="form-control" placeholder="Search by name, license, address, or phone" />
            <button type="submit" class="btn btn-primary mt-2">Search</button>
        </form>

        <table class="data-table table table-bordered">
            <thead>
                <tr>
                    <th>Person ID</th>
                    <th>Name</th>
                    <th>License Number</th>
                    <th>Address</th>
                    <th>Phone</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['person_id']; ?></td>
                        <td><?php echo $row['name']; ?></td>
                        <td><?php echo $row['license_number']; ?></td>
                        <td><?php echo $row['address']; ?></td>
                        <td><?php echo $row['phone']; ?></td>
                        <td>
                            <a href="people.php?id=<?php echo $row['person_id']; ?>" class="btn btn-success">Update</a>
                            <a href="?delete=<?php echo $row['person_id']; ?>" onclick="return confirm('Are you sure you want to delete this person?');" class="btn btn-danger">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <?php include('footer.php'); ?>
</body>
</html>
