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
// vaccineLogic.php

// Create a connection to the database
$conn = new mysqli("localhost", "root", "", "uihdatabase") or die("Unable to connect");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['saveChanges'])) {
    // Get user input
    $vaccinationName = $_POST['vaccinationName'];
    $companyName = $_POST['companyName'];
    $requiredDoses = $_POST['requiredDoses'];
    $totalQuantity = $_POST['totalQuantity'];
    $holdAmt = $_POST['holdAmt'];
    $optionalText = $_POST['optionalText'];

    // Assume you have user information available (replace these values accordingly)
    $adminFirstName = "John";
    $adminLastName = "Doe";
    $adminID = $adminFirstName . ' ' . $adminLastName;

    // Insert data into the vaccine table
    $insertQuery = "INSERT INTO vaccine (VaccinationName, CompanyName, RequiredDoses, TotalQuantity, HoldAmt, OptionalText, AdminID)
                    VALUES ('$vaccinationName', '$companyName', $requiredDoses, $totalQuantity, $holdAmt, '$optionalText', '$adminID')";
    
    if ($conn->query($insertQuery) === TRUE) {
        echo "<p>Vaccine information added successfully.</p>";
    } else {
        echo "<p>Error adding vaccine information: " . $conn->error . "</p>";
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

