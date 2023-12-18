<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Updating Nurse Info</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>

    <div class="container mt-5">

<?php
// updateNurseLogic.php

// Create a connection to the database
$conn = new mysqli("localhost", "root", "", "uihdatabase") or die("Unable to connect");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['saveChanges'])) {
        // Extract nurse ID from the form submission
        $nurseID = intval($_POST['nurseID']);

        // Prepare the query to update the nurse information
        $query = "UPDATE nurse SET FName=?, MI=?, LName=?, Age=?, Gender=?, Username=?, Password=? WHERE EmployeeID=?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sssisssi", $_POST['fname'], $_POST['mi'], $_POST['lname'], $_POST['age'], $_POST['gender'], $_POST['username'], $_POST['password'], $nurseID);

        // Execute the query
        if ($stmt->execute()) {
            // Set success message
            echo "<p>Nurse information updated successfully.</p>";
        } else {
            echo "<p>Error updating nurse information: " . $stmt->error . "</p>";
        }

        // Close prepared statement
        $stmt->close();
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

