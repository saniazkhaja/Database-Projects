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

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get selected schedulingID
    $selectedSchedulingID = $_POST['selected_scheduling_id'];

    // Get selected vaccine
    $selectedVaccine = $_POST['vaccination_type'];

    // Get patient name from the session
    $patientName = $_SESSION['firstName'] . ' ' . $_SESSION['lastName'];
    
    // Check if the patient already has an appointment scheduled
    $existingAppointment = $conn->prepare("SELECT * FROM vaccinationschedule WHERE Patient = ?");
    $existingAppointment->bind_param("s", $patientName);
    $existingAppointment->execute();
    $result = $existingAppointment->get_result();

    // Patient found
    if ($result->num_rows > 0) {
        $user_data = $result->fetch_assoc();

        // Verify that the user isn't registering for the same vaccine
        if ($user_data['VaccinationID'] == $selectedVaccine) {
            $message = "You already have an appointment for this vaccination. If you want to make changes, go back and delete the current appointment.";
        }
    } else { // Patient not found
        // Check if the condition (TotalQuantity - HoldAmt) <= 0 is true for the selected vaccine
        $checkAvailabilitySql = "SELECT TotalQuantity, HoldAmt FROM vaccine WHERE VaccinationName = ?";
        $checkAvailabilityStmt = $conn->prepare($checkAvailabilitySql);
        $checkAvailabilityStmt->bind_param("s", $selectedVaccine);
        $checkAvailabilityStmt->execute();
        $availabilityResult = $checkAvailabilityStmt->get_result();

        if ($availabilityResult->num_rows > 0) {
            $availabilityData = $availabilityResult->fetch_assoc();

            if (($availabilityData['TotalQuantity'] - $availabilityData['HoldAmt']) <= 0) {
                $message = "Error during reservation: Unfortunately, the vaccine you want currently does not have enough stock. Check back later.";
            } else {
                // Update vaccinationschedule table with patient name
                $updateSql = "UPDATE vaccinationschedule SET Patient = ? WHERE SchedulingID = ?";
                $updateStmt = $conn->prepare($updateSql);
                $updateStmt->bind_param("si", $patientName, $selectedSchedulingID);
                $updateStmt->execute();
                $updateStmt->close();

                // Increment the HoldAmt in the vaccine table for the corresponding VaccinationName
                $incrementHoldAmtSql = "UPDATE vaccine SET HoldAmt = HoldAmt + 1 WHERE VaccinationName = ?";
                $incrementHoldAmtStmt = $conn->prepare($incrementHoldAmtSql);
                $incrementHoldAmtStmt->bind_param("s", $selectedVaccine);
                $incrementHoldAmtStmt->execute();
                $incrementHoldAmtStmt->close();

                // Set success message
                $message = "You have successfully reserved your appointment!";
            }
        } else {
            $message = "Error during reservation: Vaccine not found.";
        }

        $checkAvailabilityStmt->close();
    }

    // Close prepared statement
    $existingAppointment->close();
}

// Query to retrieve available slots
$sql = "SELECT SchedulingID, Date, Time, VaccinationID FROM vaccinationschedule WHERE Patient IS NULL";
$result = $conn->query($sql);

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vaccination Schedule</title>
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
        <h1 class="mb-4 text-center">Vaccination Schedule</h1>

        <!-- Display success or error message -->
        <?php if (!empty($message)): ?>
            <div class="alert alert-<?php echo (strpos($message, 'successfully') !== false) ? 'success' : 'danger'; ?>" role="alert">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <!-- Display the schedule in a form -->
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <form class="schedule-form" method="POST" action="vacschedule.php">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Scheduling Slot</th>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Vaccination</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><?php echo $row['SchedulingID']; ?></td>
                                <td><?php echo $row['Date']; ?></td>
                                <td><?php echo $row['Time']; ?></td>
                                <td><?php echo $row['VaccinationID']; ?></td>
                                <td>
                                    <input type="hidden" name="selected_scheduling_id" value="<?php echo $row['SchedulingID']; ?>">
                                    <input type="hidden" name="selected_date" value="<?php echo $row['Date']; ?>">
                                    <input type="hidden" name="selected_time" value="<?php echo $row['Time']; ?>">
                                    <input type="hidden" name="vaccination_type" value="<?php echo $row['VaccinationID']; ?>">
                                    <button type="submit" class="btn btn-primary">Confirm Slot</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </form>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No available slots.</p>
        <?php endif; ?>

        <!-- Back button -->
        <a href="Patient/patientDashboard.php" class="btn btn-secondary">Back</a>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

</body>
</html>
