<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Nurse Information</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>

    <div class="container mt-5">
        <h1 class="mb-4 text-center">Update Nurse Information</h1>

<?php

  // Include the logic file
  include('updateAdminNurseInfoLogic.php');

// Create a connection to the database
$conn = new mysqli("localhost", "root", "", "uihdatabase") or die("Unable to connect");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['updateNurse'])) {
    // Extract nurse ID from the form submission
    $nurseID = intval($_POST['updateNurse']);

    // Prepare the query to get the nurse information
    $query = "SELECT * FROM nurse WHERE EmployeeID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $nurseID);

    // Execute the query
    $stmt->execute();

    // Get the result
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $nurseData = $result->fetch_assoc();

        // Display nurse information in a form for update
        echo "<div class='card'>";
        echo "<div class='card-body'>";
        echo "<h4 class='card-title'>Nurse Information</h4>";
        echo "<form method='POST' action='updateAdminNurseInfoLogic.php'>";
        echo "<input type='hidden' name='nurseID' value='{$nurseData['EmployeeID']}'>";
        echo "<p><strong>Employee ID:</strong> {$nurseData['EmployeeID']}</p>";
        echo "<p><strong>First Name:</strong> <input type='text' name='fname' value='{$nurseData['FName']}' required></p>";
        echo "<p><strong>Middle Initial:</strong> <input type='text' name='mi' value='{$nurseData['MI']}' maxlength='1'></p>";
        echo "<p><strong>Last Name:</strong> <input type='text' name='lname' value='{$nurseData['LName']}' required></p>";
        echo "<p><strong>Age:</strong> <input type='number' name='age' value='{$nurseData['Age']}' required></p>";
        echo "<p><strong>Gender:</strong> <input type='text' name='gender' value='{$nurseData['Gender']}' required></p>";
        echo "<p><strong>Phone Number:</strong> {$nurseData['PhoneNumber']}</p>";
        echo "<p><strong>Address:</strong> {$nurseData['Address']}</p>";
        echo "<p><strong>Username:</strong> <input type='text' name='username' value='{$nurseData['Username']}' required></p>";
        echo "<p><strong>Password:</strong> <input type='text' name='password' value='{$nurseData['Password']}' required></p>";
        echo "<button type='submit' class='btn btn-primary' name='saveChanges'>Save Changes</button>";
        echo "</form>";
        echo "</div>";
        echo "</div>";
    
    } else {
        echo "<p>No nurse information found.</p>";
    }



}

} else {
    // Prepare the query to get the list of nurses
    $query = "SELECT * FROM nurse";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        // Display a form with a dropdown menu to choose a nurse
        echo "<form method='POST' action='{$_SERVER['PHP_SELF']}'>";
        echo "<div class='form-group'>";
        echo "<label for='updateNurse'>Select Nurse to Update:</label>";
        echo "<select class='form-control' name='updateNurse' required>";
        echo "<option value='' disabled selected>Select Nurse</option>";

        // Populate the dropdown with nurse options
        while ($row = $result->fetch_assoc()) {
            echo "<option value='{$row['EmployeeID']}'>{$row['FName']} {$row['LName']} (Employee ID: {$row['EmployeeID']})</option>";
        }

        echo "</select>";
        echo "</div>";
        echo "<button type='submit' class='btn btn-primary'>Update Nurse</button>";
        echo "</form>";
    } else {
        echo "<p>No nurse information found.</p>";
    }
}

// Close connection
$conn->close();
?>

        <!-- Add a link to navigate back to the admin management page -->
        <a href="../Admin/adminDashboard.php" class="btn btn-primary mt-3">Back to Admin Dashboard</a>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

</body>
</html>


