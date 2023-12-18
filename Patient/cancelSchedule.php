<?php
session_start();

// Create a connection to the database
$conn = new mysqli("localhost", "root", "", "uihdatabase") or die("Unable to connect");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize the message variable
$message = "";

// Handle cancellation request
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['cancel_appointment'])) {
    $selectedSchedulingID = $_POST['selected_scheduling_id'];
    $selectedVaccineID = $_POST['vaccination_id'];

    // Cancel the appointment by updating the Patient column to NULL
    $cancelSql = "UPDATE vaccinationschedule SET Patient = NULL WHERE SchedulingID = ?";
    $cancelStmt = $conn->prepare($cancelSql);
    $cancelStmt->bind_param("i", $selectedSchedulingID);

     // Increment the HoldAmt in the vaccine table for the corresponding VaccinationName
     $decrementHoldAmtSql = "UPDATE vaccine SET HoldAmt = HoldAmt - 1 WHERE VaccinationName = ?";
     $decrementHoldAmtStmt = $conn->prepare($decrementHoldAmtSql);
     $decrementHoldAmtStmt->bind_param("s", $selectedVaccineID);
     $decrementHoldAmtStmt->execute();
     $decrementHoldAmtStmt->close();

    if ($cancelStmt->execute()) {
        $message = "Appointment canceled successfully!";
    } else {
        $message = "Error canceling appointment.";
    }

    $cancelStmt->close();
}

// Query to retrieve user's scheduled vaccinations
$patientName = $_SESSION['firstName'] . ' ' . $_SESSION['lastName'];
$query = "SELECT SchedulingID, Date, Time, VaccinationID FROM vaccinationschedule WHERE Patient = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $patientName);
$stmt->execute();
$result = $stmt->get_result();

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Scheduled Vaccinations</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <!-- Custom CSS -->
    <style>
        table {
            width: 100%;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #dee2e6;
            border-collapse: collapse;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
    </style>
</head>
<body>

    <div class="container mt-5">
        <h1 class="mb-4 text-center">My Scheduled Vaccinations</h1>

        <!-- Display cancellation message -->
        <?php if (!empty($message)): ?>
            <div class="alert alert-<?php echo (strpos($message, 'successfully') !== false) ? 'success' : 'danger'; ?>" role="alert">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <!-- Display scheduled vaccinations in a table -->
        <?php if ($result->num_rows > 0): ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>Scheduling ID</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Vaccination ID</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['SchedulingID']; ?></td>
                            <td><?php echo $row['Date']; ?></td>
                            <td><?php echo $row['Time']; ?></td>
                            <td><?php echo $row['VaccinationID']; ?></td>
                            <td>
                                <form method="POST" action="">
                                    <input type="hidden" name="selected_scheduling_id" value="<?php echo $row['SchedulingID']; ?>">
                                    <input type="hidden" name="vaccination_id" value="<?php echo $row['VaccinationID']; ?>">
                                    <button type="submit" name="cancel_appointment" class="btn btn-danger">Cancel Appointment</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No scheduled vaccinations.</p>
        <?php endif; ?>

         <!-- Back button -->
         <a href="../Patient/patientDashboard.php" class="btn btn-secondary">Back</a>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

</body>
</html>
