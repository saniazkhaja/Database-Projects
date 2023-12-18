<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Updating Nurse Info</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>

    <div class="container mt-5">

<?php
// vaccineLogic.php

// Create a connection to the database
$conn = new mysqli("localhost", "root", "", "uihdatabase") or die("Unable to connect");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch vaccine data
$query = "SELECT * FROM vaccine";
$result = $conn->query($query);

// Initialize vaccine data array
$vaccineData = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $vaccineData[] = $row;
    }
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['saveChanges'])) {
    // Assume you have user information available (replace these values accordingly)
    $adminFirstName = "John";
    $adminLastName = "Doe";

    // Update AdminID with the user's name and last name
    $updateQuery = "UPDATE vaccine SET AdminID = CONCAT('$adminFirstName', ' ', '$adminLastName')";
    $conn->query($updateQuery);
}

// Close connection
$conn->close();
?>
