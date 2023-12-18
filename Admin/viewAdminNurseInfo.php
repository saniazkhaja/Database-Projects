<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Nurse Information</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>

    <div class="container mt-5">
        <h1 class="mb-4 text-center">View Nurse Information</h1>

        <?php
        // Create a connection to the database
        $conn = new mysqli("localhost", "root", "", "uihdatabase") or die("Unable to connect");

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['viewNurse'])) {
            // Extract nurse ID from the form submission
            $nurseID = intval($_POST['viewNurse']);

            // Prepare the query to get the nurse information
            $query = "SELECT * FROM nurse WHERE EmployeeID = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $nurseID);

            // Execute the query
            $stmt->execute();

            // Get the result
            $result = $stmt->get_result();

            // Check if nurse information is found
            if ($result->num_rows > 0) {
                $nurseData = $result->fetch_assoc();

                // Display nurse information
                echo "<div class='card'>";
                echo "<div class='card-body'>";
                echo "<h4 class='card-title'>Nurse Information</h4>";
                echo "<p><strong>Employee ID:</strong> {$nurseData['EmployeeID']}</p>";
                echo "<p><strong>First Name:</strong> {$nurseData['FName']}</p>";
                echo "<p><strong>Middle Initial:</strong> {$nurseData['MI']}</p>";
                echo "<p><strong>Last Name:</strong> {$nurseData['LName']}</p>";
                echo "<p><strong>Age:</strong> {$nurseData['Age']}</p>";
                echo "<p><strong>Gender:</strong> {$nurseData['Gender']}</p>";
                echo "<p><strong>Phone Number:</strong> {$nurseData['PhoneNumber']}</p>";
                echo "<p><strong>Address:</strong> {$nurseData['Address']}</p>";
                echo "<p><strong>Username:</strong> {$nurseData['Username']}</p>";
                echo "<p><strong>Password:</strong> {$nurseData['Password']}</p>";

                // Fetch and display nurse's scheduled vaccinations
                $nurseFullName = $nurseData['FName'] . ' ' . $nurseData['LName'];
                $scheduledVaccinationsQuery = "SELECT * FROM nursescheduling WHERE NurseID = ?";
                $scheduledVaccinationsStmt = $conn->prepare($scheduledVaccinationsQuery);
                $scheduledVaccinationsStmt->bind_param("s", $nurseFullName);
                $scheduledVaccinationsStmt->execute();
                $scheduledVaccinationsResult = $scheduledVaccinationsStmt->get_result();

                if ($scheduledVaccinationsResult->num_rows > 0) {
                    // Display a table with nurse's scheduled vaccinations
                    echo "<h4 class='card-title mt-3'>Nurse's Scheduled Vaccinations</h4>";
                    echo "<table class='table'>";
                    echo "<thead>";
                    echo "<tr>";
                    echo "<th>Scheduling ID</th>";
                    echo "<th>Date</th>";
                    echo "<th>Time</th>";
                    echo "</tr>";
                    echo "</thead>";
                    echo "<tbody>";

                    while ($row = $scheduledVaccinationsResult->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>{$row['NurseSchedulingID']}</td>";
                        echo "<td>{$row['Date']}</td>";
                        echo "<td>{$row['Time']}</td>";
                        echo "</tr>";
                    }

                    echo "</tbody>";
                    echo "</table>";
                } else {
                    echo "<p>No scheduled vaccinations for this nurse.</p>";
                }

                // Close prepared statement
                $scheduledVaccinationsStmt->close();

                echo "</div>";
                echo "</div>";
            } else {
                echo "<p>No nurse information found.</p>";
            }

            // Close prepared statement
            $stmt->close();
        } else {
            // Prepare the query to get the list of nurses
            $query = "SELECT * FROM nurse";
            $result = $conn->query($query);

            if ($result->num_rows > 0) {
                // Display a form with a dropdown menu to choose a nurse
                echo "<form method='POST' action='{$_SERVER['PHP_SELF']}'>";
                echo "<div class='form-group'>";
                echo "<label for='viewNurse'>Select Nurse to View:</label>";
                echo "<select class='form-control' name='viewNurse' required>";
                echo "<option value='' disabled selected>Select Nurse</option>";

                // Populate the dropdown with nurse options
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='{$row['EmployeeID']}'>{$row['FName']} {$row['LName']} (Employee ID: {$row['EmployeeID']})</option>";
                }

                echo "</select>";
                echo "</div>";
                echo "<button type='submit' class='btn btn-primary'>View Nurse Information</button>";
                echo "</form>";
            } else {
                echo "<p>No nurse information found.</p>";
            }
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
