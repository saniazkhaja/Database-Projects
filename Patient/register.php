<?php
session_start();
$errors = array(); 
$success = array();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
   
    // Create connection
    $conn = new mysqli("localhost", "root", "", "uihdatabase") or die("Unable to connect");

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Grabbing data from form
    $fname = $_POST['fname'];
    $mi = $_POST['mi'];
    $lname = $_POST['lname'];
    $ssn = $_POST['ssn'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $race = $_POST['race'];
    $occupation = $_POST['occupation'];
    $medicalhistory = $_POST['medicalHistory'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];

    // Checking that user filled in required fields on form
    if (empty($fname)) { array_push($errors, "First Name is required"); }
    if (empty($lname)) { array_push($errors, "Last Name is required"); }
    if (empty($ssn)) { array_push($errors, "Social Security is required"); }
    if (empty($age)) { array_push($errors, "Age is required"); }
    if (empty($gender)) { array_push($errors, "Gender is required"); }
    if (empty($race)) { array_push($errors, "Race is required"); }
    if (empty($medicalhistory)) { array_push($errors, "Medical History is required"); }
    if (empty($phone)) { array_push($errors, "Phone Number is required"); }
    if (empty($address)) { array_push($errors, "Address is required"); }
    if (empty($username)) { array_push($errors, "Username is required"); }
    if (empty($password)) { array_push($errors, "Password is required"); }
    if (empty($confirmPassword)) { array_push($errors, "Confirm password is required"); }
    if ($password != $confirmPassword) { array_push($errors, "Passwords do not match"); }

    // Setting up the query 
    $stmt = $conn->prepare("SELECT * FROM patient WHERE SSN = ?");
    $stmt->bind_param("s", $ssn);

    // Execute the query
    $stmt->execute();

    // Get the result
    $result = $stmt->get_result();

   // Check if the patient exists
    if ($result->num_rows > 0) {
        array_push($errors, "You already have an account");
    }

    $stmt2 = $conn->prepare("INSERT INTO patient (SSN, FName, MI, LName, Age, Gender, Race, Occupation, PhoneNumber, Address, MedicalHistory, Username, Password) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    if (count($errors) == 0) {
        $stmt2->bind_param("sssssssssssss", $ssn,$fname, $mi, $lname, $age, $gender, $race, $occupation, $phone, $address, $medicalhistory, $username, $password);
        $stmt2->execute();

        array_push($success, "Successfully Registered"); 
    }



     //Close prepared statement and connection
     $stmt->close();
     $stmt2->close();
     $conn->close();

    }
?>
