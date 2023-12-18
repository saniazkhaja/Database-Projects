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

    <div class="container mt-5">
        <h1 class="mb-4 text-center">View Nurse Information</h1>

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
            // Create connection
            $conn = new mysqli("localhost", "root", "", "uihdatabase") or die("Unable to connect");

            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Assuming you have a session variable storing the nurse's EmployeeID after login
            $loggedInEmployeeID = $_SESSION['employeeID'];

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
                echo "<p><strong>Name:</strong> {$nurseData['FName']} {$nurseData['MI']} {$nurseData['LName']}</p>";
                echo "<p><strong>Age:</strong> {$nurseData['Age']}</p>";
                echo "<p><strong>Gender:</strong> {$nurseData['Gender']}</p>";
                echo "<p><strong>Phone Number:</strong> {$nurseData['PhoneNumber']}</p>";
                echo "<p><strong>Address:</strong> {$nurseData['Address']}</p>";
                echo "<p><strong>Username:</strong> {$nurseData['Username']}</p>";
                echo "<p><strong>Password:</strong> {$nurseData['Password']}</p>";
                echo "</div>";
                echo "</div>";

                // Add a button to navigate back to the nurse dashboard
                echo "<a href='nurseDashboard.php' class='btn btn-primary mt-3'>Back to Nurse Dashboard</a>";
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

    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

</body>
</html>
