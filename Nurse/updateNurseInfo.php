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

<?php
session_start();

// Create a connection to the database
$conn = new mysqli("localhost", "root", "", "uihdatabase") or die("Unable to connect");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Assuming you have a session or some form of user authentication
if (isset($_SESSION['username'])) {
    // Assuming you have a session variable storing the nurse's EmployeeID after login
    $loggedInEmployeeID = $_SESSION['employeeID'];

    // Check if the form is submitted for updates
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Handle the form submission to update nurse information
        // Grabbing data from the form
        $address = $_POST['address'];
        $phone = $_POST['phone'];

        // Update the nurse information in the database
        $updateSql = "UPDATE nurse SET Address=?, PhoneNumber=? WHERE EmployeeID=?";
        $updateStmt = $conn->prepare($updateSql);
        $updateStmt->bind_param("sss", $address, $phone, $loggedInEmployeeID);
        $updateStmt->execute();
        $updateStmt->close();

        // Redirect to the view page after updating
        header("Location: viewNurseInfo.php");
        exit();
    }

    // Prepare the query
    $stmt = $conn->prepare("SELECT * FROM nurse WHERE EmployeeID = ?");
    $stmt->bind_param("s", $loggedInEmployeeID);

    // Execute the query
    $stmt->execute();

    // Get the result
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $nurseData = $result->fetch_assoc();

        // Display nurse information
        echo "<div class='card'>";
        echo "<div class='card-body'>";
        echo "<h4 class='card-title'>Nurse Information</h4>";
        echo "<form method='POST' action='updateNurseInfo.php'>";
        echo "<p><strong>Address:</strong> <textarea name='address' required>{$nurseData['Address']}</textarea></p>";
        echo "<p><strong>Phone Number:</strong> <input type='tel' name='phone' value='{$nurseData['PhoneNumber']}' required></p>";
        echo "<button type='submit' class='btn btn-primary'>Save Changes</button>";
        echo "</form>";
        echo "</div>";
        echo "</div>";
    } else {
        echo "<p>No nurse information found.</p>";
    }

    // Close prepared statement and connection
    $stmt->close();
    $conn->close();
} else {
    echo "<p>Please log in to view nurse information.</p>";
}
?>
