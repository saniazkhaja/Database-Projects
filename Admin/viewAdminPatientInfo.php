<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Patient Information</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>

    <div class="container mt-5">
        <h1 class="mb-4 text-center">View Patient Information</h1>

        <?php
        // Create a connection to the database
        $conn = new mysqli("localhost", "root", "", "uihdatabase") or die("Unable to connect");

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['viewPatient'])) {
            // Extract SSN from the form submission
            $ssn = $conn->real_escape_string($_POST['viewPatient']);

            // Prepare the query to get the patient information
            $query = "SELECT * FROM patient WHERE SSN = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("s", $ssn);

            // Execute the query
            $stmt->execute();

            // Get the result
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $patientData = $result->fetch_assoc();

                // Display patient information
                echo "<div class='card'>";
                echo "<div class='card-body'>";
                echo "<h4 class='card-title'>Patient Information</h4>";
                echo "<p><strong>SSN:</strong> {$patientData['SSN']}</p>";
                echo "<p><strong>First Name:</strong> {$patientData['FName']}</p>";
                echo "<p><strong>Middle Initial:</strong> {$patientData['MI']}</p>";
                echo "<p><strong>Last Name:</strong> {$patientData['LName']}</p>";
                echo "<p><strong>Age:</strong> {$patientData['Age']}</p>";
                echo "<p><strong>Gender:</strong> {$patientData['Gender']}</p>";
                echo "<p><strong>Race:</strong> {$patientData['Race']}</p>";
                echo "<p><strong>Occupation:</strong> {$patientData['Occupation']}</p>";
                echo "<p><strong>Phone Number:</strong> {$patientData['PhoneNumber']}</p>";
                echo "<p><strong>Address:</strong> {$patientData['Address']}</p>";
                echo "<p><strong>Medical History:</strong> {$patientData['MedicalHistory']}</p>";
                echo "<p><strong>Username:</strong> {$patientData['Username']}</p>";
                echo "<p><strong>Password:</strong> {$patientData['Password']}</p>";

                // Display patient's vaccination history
                echo "<h4 class='mt-4'>Vaccination History</h4>";
                $vaccinationHistoryQuery = "SELECT * FROM vaccinationrecord WHERE PatientSSN = ?";
                $vaccinationHistoryStmt = $conn->prepare($vaccinationHistoryQuery);
                $vaccinationHistoryStmt->bind_param("s", $ssn);
                $vaccinationHistoryStmt->execute();
                $vaccinationHistoryResult = $vaccinationHistoryStmt->get_result();

                if ($vaccinationHistoryResult->num_rows > 0) {
                    // Display vaccination history table
                    echo "<table class='table'>";
                    echo "<thead>";
                    echo "<tr>";
                    echo "<th>Administered Date</th>";
                    echo "<th>Administered Time</th>";
                    echo "<th>Vaccination Dose</th>";
                    echo "<th>Vaccination Type</th>";
                    echo "</tr>";
                    echo "</thead>";
                    echo "<tbody>";
                    while ($vaccinationHistoryRow = $vaccinationHistoryResult->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>{$vaccinationHistoryRow['AdministeredDate']}</td>";
                        echo "<td>{$vaccinationHistoryRow['AdministeredTime']}</td>";
                        echo "<td>{$vaccinationHistoryRow['VaccDose']}</td>";
                        echo "<td>{$vaccinationHistoryRow['VaccType']}</td>";
                        echo "</tr>";
                    }
                    echo "</tbody>";
                    echo "</table>";
                } else {
                    echo "<p>No vaccination history found.</p>";
                }

                // Display patient's scheduled vaccination appointments
                echo "<h4 class='mt-4'>Scheduled Vaccination Appointments</h4>";
                $patientFullName = $patientData['FName'] . ' ' . $patientData['LName'];

                $scheduledAppointmentsQuery = "SELECT * FROM vaccinationschedule WHERE Patient = ?";
                $scheduledAppointmentsStmt = $conn->prepare($scheduledAppointmentsQuery);
                $scheduledAppointmentsStmt->bind_param("s", $patientFullName);
                $scheduledAppointmentsStmt->execute();
                $scheduledAppointmentsResult = $scheduledAppointmentsStmt->get_result();

                if ($scheduledAppointmentsResult->num_rows > 0) {
                    // Display scheduled appointments table
                    echo "<table class='table'>";
                    echo "<thead>";
                    echo "<tr>";
                    echo "<th>Scheduling ID</th>";
                    echo "<th>Date</th>";
                    echo "<th>Time</th>";
                    echo "<th>Vaccination ID</th>";
                    echo "</tr>";
                    echo "</thead>";
                    echo "<tbody>";
                    while ($scheduledAppointmentRow = $scheduledAppointmentsResult->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>{$scheduledAppointmentRow['SchedulingID']}</td>";
                        echo "<td>{$scheduledAppointmentRow['Date']}</td>";
                        echo "<td>{$scheduledAppointmentRow['Time']}</td>";
                        echo "<td>{$scheduledAppointmentRow['VaccinationID']}</td>";
                        echo "</tr>";
                    }
                    echo "</tbody>";
                    echo "</table>";
                } else {
                    echo "<p>No scheduled vaccination appointments found.</p>";
                }

                echo "</div>";
                echo "</div>";
            } else {
                echo "<p>No patient information found.</p>";
            }

            // Close prepared statements
            $stmt->close();
            $vaccinationHistoryStmt->close();
            $scheduledAppointmentsStmt->close();
        } else {
            // Prepare the query to get the list of patients
            $query = "SELECT * FROM patient";
            $result = $conn->query($query);

            if ($result->num_rows > 0) {
                // Display a form with a dropdown menu to choose a patient
                echo "<form method='POST' action='{$_SERVER['PHP_SELF']}'>";
                echo "<div class='form-group'>";
                echo "<label for='viewPatient'>Select Patient to View:</label>";
                echo "<select class='form-control' name='viewPatient' required>";
                echo "<option value='' disabled selected>Select Patient</option>";

                // Populate the dropdown with patient options
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='{$row['SSN']}'>{$row['FName']} {$row['LName']} (SSN: {$row['SSN']})</option>";
                }

                echo "</select>";
                echo "</div>";
                echo "<button type='submit' class='btn btn-primary'>View Patient Information</button>";
                echo "</form>";
            } else {
                echo "<p>No patient information found.</p>";
            }
        }

        // Close connection
        $conn->close();
        ?>

        <!-- Add a link to navigate back to the admin dashboard or another relevant page -->
        <a href="../Admin/adminDashboard.php" class="btn btn-primary mt-3">Back to Admin Dashboard</a>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

</body>
</html>
