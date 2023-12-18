<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nurse Management</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>

    <div class="container mt-5">
        <h1 class="mb-4 text-center">Nurse Management</h1>

        <?php
        // Create a connection to the database
        $conn = new mysqli("localhost", "root", "", "uihdatabase") or die("Unable to connect");

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Check if nurse ID is provided for deletion
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['deleteNurse'])) {
            $nurseID = intval($_POST['deleteNurse']);

            // Prepare the SQL statement to delete the nurse
            $deleteSql = "DELETE FROM nurse WHERE EmployeeID = ?";
            $deleteStmt = $conn->prepare($deleteSql);
            $deleteStmt->bind_param("i", $nurseID);

            // Execute the query
            if ($deleteStmt->execute()) {
                echo "<p>Nurse deleted successfully.</p>";
            } else {
                echo "<p>Error deleting nurse. Please try again.</p>";
            }

            // Close prepared statement
            $deleteStmt->close();
        }

        // Prepare the query to get the list of nurses
        $query = "SELECT * FROM nurse";
        $result = $conn->query($query);

        if ($result->num_rows > 0) {
            // Display nurse information and provide a delete button for each nurse
            echo "<table class='table'>";
            echo "<thead>";
            echo "<tr><th>Employee ID</th><th>First Name</th><th>Last Name</th><th>Actions</th></tr>";
            echo "</thead>";
            echo "<tbody>";

            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>{$row['EmployeeID']}</td>";
                echo "<td>{$row['FName']}</td>";
                echo "<td>{$row['LName']}</td>";
                echo "<td>
                        <form method='POST' action='{$_SERVER['PHP_SELF']}'>
                            <input type='hidden' name='deleteNurse' value='{$row['EmployeeID']}'>
                            <button type='submit' class='btn btn-danger'>Delete</button>
                        </form>
                    </td>";
                echo "</tr>";
            }

            echo "</tbody>";
            echo "</table>";
        } else {
            echo "<p>No nurse information found.</p>";
        }

        // Close connection
        $conn->close();
        ?>

        <!-- Add a link to navigate back to the nurse management page -->
        <a href="../Admin/adminDashboard.php" class="btn btn-primary mt-3">Back to Admin Dashboard</a>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

</body>
</html>
