<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Record Vaccination</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>

<div class="container mt-5">

<?php
// Create a connection to the database
$conn = new mysqli("localhost", "root", "", "uihdatabase") or die("Unable to connect");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['saveChanges'])) {
    // Get nurse input
    $administeredDate = $_POST['administeredDate'];
    $administeredTime = $_POST['administeredTime'];
    $vaccDose = $_POST['vaccDose'];
    $nurseID = $_POST['nurseID'];
    $patientSSN = $_POST['patientSSN'];
    $vaccinationID = $_POST['vaccinationID'];

    // Insert vaccination record into the table
    $insertQuery = "INSERT INTO vaccinationrecord (AdministeredDate, AdministeredTime, VaccDose, NurseID, PatientSSN, VaccinationID) 
                    VALUES ('$administeredDate', '$administeredTime', $vaccDose, '$nurseID', '$patientSSN', '$vaccinationID')";

     // Increment the HoldAmt in the vaccine table for the corresponding VaccinationName
     $decrementHoldAmtSql = "UPDATE vaccine SET TotalQuantity = TotalQuantity - 1, HoldAmt = HoldAmt - 1 WHERE VaccinationName = ?";
     $decrementHoldAmtStmt = $conn->prepare($decrementHoldAmtSql);
     $decrementHoldAmtStmt->bind_param("s", $vaccinationID);
     $decrementHoldAmtStmt->execute();
     $decrementHoldAmtStmt->close();

    if ($conn->query($insertQuery) === TRUE) {
        echo "<p>Vaccination recorded successfully.</p>";
    } else {
        echo "<p>Error recording vaccination: " . $conn->error . "</p>";
    }
}

// Close connection
$conn->close();
?>

<form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <div class="form-group">
        <label for="administeredDate">Administered Date:</label>
        <input type="date" class="form-control" name="administeredDate" required>
    </div>
    <div class="form-group">
        <label for="administeredTime">Administered Time:</label>
        <input type="time" class="form-control" name="administeredTime" required>
    </div>
    <div class="form-group">
        <label for="vaccDose">Vaccination Dose:</label>
        <input type="number" class="form-control" name="vaccDose" required>
    </div>
    <div class="form-group">
        <label for="nurseID">Nurse ID:</label>
        <input type="text" class="form-control" name="nurseID" required>
    </div>
    <div class="form-group">
        <label for="patientSSN">Patient SSN:</label>
        <input type="text" class="form-control" name="patientSSN" required>
    </div>
    <div class="form-group">
        <label for="vaccinationID">Vaccination ID:</label>
        <input type="text" class="form-control" name="vaccinationID" required>
    </div>
    <button type="submit" class="btn btn-primary" name="saveChanges">Save Changes</button>
</form>

<!-- Add a link to navigate back to the nurse dashboard -->
<a href="../Nurse/nurseDashboard.php" class="btn btn-primary mt-3">Back to Nurse Dashboard</a>

</div>

<!-- Bootstrap JS and dependencies -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

</body>
</html>
