<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nurse Registration</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>

    <div class="container mt-5">
        <h1 class="mb-4 text-center">Nurse Registration</h1>

        <?php
        // Check if the form is submitted for nurse registration
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Validate and sanitize input data (you can add more validation as needed)
            $employeeID = intval($_POST['employeeID']);
            $fname = htmlspecialchars($_POST['fname']);
            $mi = htmlspecialchars($_POST['mi']);
            $lname = htmlspecialchars($_POST['lname']);
            $age = intval($_POST['age']);
            $gender = htmlspecialchars($_POST['gender']);
            $phone = htmlspecialchars($_POST['phone']);
            $address = htmlspecialchars($_POST['address']);
            $username = htmlspecialchars($_POST['username']);
            $password = htmlspecialchars($_POST['password']);

            // Create a connection to the database
            $conn = new mysqli("localhost", "root", "", "uihdatabase") or die("Unable to connect");

            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Prepare the SQL statement to insert a new nurse
            $insertSql = "INSERT INTO nurse (EmployeeID, FName, MI, LName, Age, Gender, PhoneNumber, Address, Username, Password) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $insertStmt = $conn->prepare($insertSql);
            $insertStmt->bind_param("isssisssss", $employeeID, $fname, $mi, $lname, $age, $gender, $phone, $address, $username, $password);

            // Execute the query
            if ($insertStmt->execute()) {
                echo "<p>Nurse registered successfully.</p>";
            } else {
                echo "<p>Error registering nurse. Please try again.</p>";
            }

            // Close prepared statement and connection
            $insertStmt->close();
            $conn->close();
        }
        ?>

        <!-- Nurse Registration Form -->
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Register Nurse</h4>
                <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <div class="form-group">
                        <label for="employeeID">Employee ID:</label>
                        <input type="text" class="form-control" name="employeeID" required>
                    </div>
                    <div class="form-group">
                        <label for="fname">First Name:</label>
                        <input type="text" class="form-control" name="fname" required>
                    </div>
                    <div class="form-group">
                        <label for="mi">Middle Initial:</label>
                        <input type="text" class="form-control" name="mi" maxlength="1">
                    </div>
                    <div class="form-group">
                        <label for="lname">Last Name:</label>
                        <input type="text" class="form-control" name="lname" required>
                    </div>
                    <div class="form-group">
                        <label for="age">Age:</label>
                        <input type="number" class="form-control" name="age" required>
                    </div>
                    <div class="form-group">
                        <label for="gender">Gender:</label>
                        <input type="text" class="form-control" name="gender" required>
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone Number:</label>
                        <input type="tel" class="form-control" name="phone" required>
                    </div>
                    <div class="form-group">
                        <label for="address">Address:</label>
                        <textarea class="form-control" name="address" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="username">Username:</label>
                        <input type="text" class="form-control" name="username" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password:</label>
                        <input type="password" class="form-control" name="password" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Register Nurse</button>
                </form>
            </div>
        </div>
        <a href="../Admin/adminDashboard.php" class="btn btn-primary mt-3">Back to Admin Dashboard</a>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

</body>
</html>
