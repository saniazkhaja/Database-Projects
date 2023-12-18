<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modify Vaccine Information</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>

<div class="container mt-5">

    <?php
    // vaccineModify.php

    // Create a connection to the database
    $conn = new mysqli("localhost", "root", "", "uihdatabase") or die("Unable to connect");

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Fetch unique VaccinationNames from the vaccine table
    $query = "SELECT DISTINCT VaccinationName FROM vaccine";
    $result = $conn->query($query);

    // Initialize vaccine names array
    $vaccineNames = [];

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $vaccineNames[] = $row['VaccinationName'];
        }
    }
    ?>

    <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <div class="form-group">
            <label for="vaccineName">Select VaccinationName:</label>
            <select class="form-control" name="vaccineName" required>
                <option value="" disabled selected>Select VaccinationName</option>
                <?php
                foreach ($vaccineNames as $name) {
                    echo "<option value='$name'>$name</option>";
                }
                ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary" name="modify">Modify</button>
    </form>

    <?php
    // Handle form submission for Modify button
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['modify'])) {
        $selectedVaccineName = $_POST['vaccineName'];

        // Fetch information for the selected VaccinationName
        $query = "SELECT * FROM vaccine WHERE VaccinationName = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $selectedVaccineName);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $vaccineData = $result->fetch_assoc();

            // Display vaccine information in a form for update
            echo "<div class='card mt-3'>";
            echo "<div class='card-body'>";
            echo "<h4 class='card-title'>Vaccine Information</h4>";
            echo "<form method='POST' action='{$_SERVER['PHP_SELF']}'>";
            echo "<input type='hidden' name='vaccineName' value='{$vaccineData['VaccinationName']}'>";
            echo "<p><strong>Vaccination Name:</strong> {$vaccineData['VaccinationName']}</p>";
            echo "<p><strong>Company Name:</strong> <input type='text' name='companyName' value='{$vaccineData['CompanyName']}' required></p>";
            echo "<p><strong>Required Doses:</strong> <input type='number' name='requiredDoses' value='{$vaccineData['RequiredDoses']}' required></p>";
            echo "<p><strong>Total Quantity:</strong> <input type='number' name='totalQuantity' value='{$vaccineData['TotalQuantity']}' required></p>";
            echo "<p><strong>Hold Amount:</strong> <input type='number' name='holdAmt' value='{$vaccineData['HoldAmt']}' required></p>";
            echo "<p><strong>Optional Text:</strong> <input type='text' name='optionalText' value='{$vaccineData['OptionalText']}'></p>";
            echo "<p><strong>Admin ID:</strong> {$vaccineData['AdminID']}</p>";
            echo "<button type='submit' class='btn btn-primary' name='saveChanges'>Save Changes</button>";
            echo "</form>";
            echo "</div>";
            echo "</div>";
        } else {
            echo "<p>No information found for the selected VaccinationName.</p>";
        }

        // Close prepared statement
        $stmt->close();
    }

    // Handle form submission for Save Changes button
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['saveChanges'])) {
        // Get user input
        $companyName = $_POST['companyName'];
        $requiredDoses = $_POST['requiredDoses'];
        $totalQuantity = $_POST['totalQuantity'];
        $holdAmt = $_POST['holdAmt'];
        $optionalText = $_POST['optionalText'];
        $vaccineName = $_POST['vaccineName'];

        // Update Vaccine information in the table
        $updateQuery = "UPDATE vaccine SET 
                        CompanyName = '$companyName',
                        RequiredDoses = $requiredDoses,
                        TotalQuantity = $totalQuantity,
                        HoldAmt = $holdAmt,
                        OptionalText = '$optionalText'
                        WHERE VaccinationName = '$vaccineName'";

        if ($conn->query($updateQuery) === TRUE) {
            echo "<p>Vaccine information updated successfully.</p>";
        } else {
            echo "<p>Error updating vaccine information: " . $conn->error . "</p>";
        }
    }

    // Close connection
    $conn->close();
    ?>

    <!-- Add a link to navigate back to the admin dashboard -->
    <a href="../Admin/adminDashboard.php" class="btn btn-primary mt-3">Back to Admin Dashboard</a>
</div>

<!-- Bootstrap JS and dependencies -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

</body>
</html>
