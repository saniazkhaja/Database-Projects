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
        session_start();

        // Create a connection to the database
        $conn = new mysqli("localhost", "root", "", "uihdatabase") or die("Unable to connect");

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Assuming you have a session or some form of user authentication
        if (isset($_SESSION['username'])) {
            // Assuming you have a session variable storing the patient's SSN after login
            $loggedInSSN = $_SESSION['ssn'];

            // Retrieve patient information
            $patientQuery = "SELECT * FROM patient WHERE SSN = ?";
            $stmtPatient = $conn->prepare($patientQuery);
            $stmtPatient->bind_param("s", $loggedInSSN);
            $stmtPatient->execute();
            $resultPatient = $stmtPatient->get_result();

            // Retrieve vaccination history
            $vaccinationQuery = "SELECT * FROM vaccinationrecord WHERE PatientSSN = ?";
            $stmtVaccination = $conn->prepare($vaccinationQuery);
            $stmtVaccination->bind_param("s", $loggedInSSN);
            $stmtVaccination->execute();
            $resultVaccination = $stmtVaccination->get_result();

            if ($resultPatient->num_rows > 0) {
                $patientData = $resultPatient->fetch_assoc();

                // Display patient information
                echo "<div class='card'>";
                echo "<div class='card-body'>";
                echo "<h4 class='card-title'>Patient Information</h4>";
                echo "<p><strong>Name:</strong> {$patientData['FName']} {$patientData['MI']} {$patientData['LName']}</p>";
                echo "<p><strong>Age:</strong> {$patientData['Age']}</p>";
                echo "<p><strong>Gender:</strong> {$patientData['Gender']}</p>";
                echo "<p><strong>Race:</strong> {$patientData['Race']}</p>";
                echo "<p><strong>Occupation:</strong> {$patientData['Occupation']}</p>";
                echo "<p><strong>Phone Number:</strong> {$patientData['PhoneNumber']}</p>";
                echo "<p><strong>Address:</strong> {$patientData['Address']}</p>";
                echo "<p><strong>Medical History:</strong> {$patientData['MedicalHistory']}</p>";
                echo "<p><strong>Username:</strong> {$patientData['Username']}</p>";
                echo "<p><strong>Password:</strong> {$patientData['Password']}</p>";
                echo "</div>";
                echo "</div>";

                // Display vaccination history table
                if ($resultVaccination->num_rows > 0) {
                    echo "<div class='card mt-4'>";
                    echo "<div class='card-body'>";
                    echo "<h4 class='card-title'>Vaccination History</h4>";
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

                    while ($row = $resultVaccination->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>{$row['AdministeredDate']}</td>";
                        echo "<td>{$row['AdministeredTime']}</td>";
                        echo "<td>{$row['VaccDose']}</td>";
                        echo "<td>{$row['VaccType']}</td>";
                        echo "</tr>";
                    }

                    echo "</tbody>";
                    echo "</table>";
                    echo "</div>";
                    echo "</div>";
                } else {
                    echo "<p>No vaccination history found.</p>";
                }

                // Add a button to navigate back to the patient dashboard
                echo "<a href='patientDashboard.php' class='btn btn-primary mt-3'>Back to Patient Dashboard</a>";
            } else {
                echo "<p>No patient information found.</p>";
            }

            // Close prepared statements
            $stmtPatient->close();
            $stmtVaccination->close();
        } else {
            echo "<p>Please log in to view patient information.</p>";
        }

        // Close connection
        $conn->close();
        ?>

    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

</body>
</html>
