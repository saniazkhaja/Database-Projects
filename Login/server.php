<?php
session_start();
$errors = array(); 

// if (isset($_POST['register_user'])) {
//     header('location: register_patient.php');
// }

if (isset($_POST['login_user'])) {

    // Grabbing 'userType' field
    $userType = $_POST['usertype'];

    // Get credentials from the form
    $username = $_POST['username'];
    $password = $_POST['password'];


    // Create connection
    $conn = new mysqli("localhost", "root", "", "uihdatabase") or die("Unable to connect");

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    if (empty($username)) {
        array_push($errors, "Username is required");
    }
    if (empty($password)) {
        array_push($errors, "Password is required");
    }
   
   
    // Use prepared statements to prevent SQL injection
      $stmt = $conn->prepare("SELECT * FROM $userType WHERE Username = ?");
      $stmt->bind_param("s", $username);

      // Execute the query
      $stmt->execute();

      // Get the result
      $result = $stmt->get_result();

    // Check if the user exists
    if ($result->num_rows > 0) {
        $user_data = $result->fetch_assoc();

        // Verify the password
        if ($password == $user_data['Password']) {
            // Authentication successful
            $_SESSION['username'] = $username;
            $_SESSION['userType'] = $userType;

           
            // Store first name and last name in the session
            $_SESSION['firstName'] = $user_data['FName'];
            $_SESSION['lastName'] = $user_data['LName'];
            $_SESSION['ssn'] = $user_data['SSN'];
            $_SESSION['employeeID'] = $user_data['EmployeeID'];
          
           
            // Redirect based on user type
            switch ($userType) {
                case 'admin':
                    header('location: ../Admin/adminDashboard.php');
                    break;
                case 'nurse':
                    header('location: ../Nurse/nurseDashboard.php');
                    break;
                case 'patient':
                    header('location: ../Patient/patientDashboard.php');
                    break;
            }

            exit();
        } else {
            array_push($errors, "Invalid Username/Password");
        }
    } else {
        array_push($errors, "Invalid Username/Password");
    }

    //Close prepared statement and connection
    $stmt->close();
    $conn->close();
}

?>
