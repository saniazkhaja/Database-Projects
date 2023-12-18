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
    
    // Check if the form is submitted for updates
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Handle the form submission to update patient information
        echo "Inside POST Request Method"; // Add this line for debugging
        // Grabbing data from the form
        $fname = $_POST['fname'];
        $mi = $_POST['mi'];
        $lname = $_POST['lname'];
        $age = $_POST['age'];
        $gender = $_POST['gender'];
        $race = $_POST['race'];
        $occupation = $_POST['occupation'];
        $phone = $_POST['phone'];
        $address = $_POST['address'];
        $medicalhistory = $_POST['medicalhistory'];
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Update the patient information in the database
        $updateSql = "UPDATE patient SET FName=?, MI=?, LName=?, Age=?, Gender=?, Race=?, Occupation=?, PhoneNumber=?, Address=?, MedicalHistory=?, Username=?, Password=? WHERE SSN=?";
        $updateStmt = $conn->prepare($updateSql);
        $updateStmt->bind_param("sssssssssssss", $fname, $mi, $lname, $age, $gender, $race, $occupation, $phone, $address, $medicalhistory, $username, $password, $loggedInSSN);
        $updateStmt->execute();
        $updateStmt->close();

        // Redirect to the view page after updating
        header("Location: viewpatientInfo.php");
        exit();
    }

    // Prepare the query
    $stmt = $conn->prepare("SELECT * FROM patient WHERE SSN = ?");
    $stmt->bind_param("s", $loggedInSSN);

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
        echo "<form method='POST' action='updatePatientInfo.php'>";
        echo "<p><strong>Name:</strong> <input type='text' name='fname' value='{$patientData['FName']}' required> <input type='text' name='mi' value='{$patientData['MI']}' maxlength='1'> <input type='text' name='lname' value='{$patientData['LName']}' required></p>";
        echo "<p><strong>Age:</strong> <input type='number' name='age' value='{$patientData['Age']}' required></p>";
        echo "<p><strong>Gender:</strong> <input type='text' name='gender' value='{$patientData['Gender']}' required></p>";
        echo "<p><strong>Race:</strong> <input type='text' name='race' value='{$patientData['Race']}' required></p>";
        echo "<p><strong>Occupation:</strong> <input type='text' name='occupation' value='{$patientData['Occupation']}'></p>";
        echo "<p><strong>Phone Number:</strong> <input type='tel' name='phone' value='{$patientData['PhoneNumber']}' required></p>";
        echo "<p><strong>Address:</strong> <textarea name='address' required>{$patientData['Address']}</textarea></p>";
        echo "<p><strong>Medical History:</strong> <textarea name='medicalhistory' required>{$patientData['MedicalHistory']}</textarea></p>";
        echo "<p><strong>Username:</strong> <input type='text' name='username' value='{$patientData['Username']}' required></p>";
        echo "<p><strong>Password:</strong> <input type='text' name='password' value='{$patientData['Password']}' required></p>";
        echo "<button type='submit' class='btn btn-primary'>Save Changes</button>";
        echo "</form>";
        echo "</div>";
        echo "</div>";
    } else {
        echo "<p>No patient information found.</p>";
    }

    // Close prepared statement and connection
    $stmt->close();
    $conn->close();
} else {
    echo "<p>Please log in to view patient information.</p>";
}
?>
