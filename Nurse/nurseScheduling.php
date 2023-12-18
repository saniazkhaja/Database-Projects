<?php
session_start();

// Create a connection to the database (replace these credentials with your actual database credentials)
$conn = new mysqli("localhost", "root", "", "uihdatabase") or die("Unable to connect");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize the message variable
$message = "";

// Handle nurse reservation
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['reserve_date'])) {
        $dateToReserve = $_POST['reserve_date'];
        $timeToReserve = $_POST['reserve_time'];
        $nurseSchedulingID = $_POST['schedulingID'];

        // Grab Nurse's Name
        $nurseName = $_SESSION['firstName'] . ' ' . $_SESSION['lastName'];

        // Check if the nurse is already scheduled for the selected Date and Time
        $checkExistingSchedule = $conn->prepare("SELECT * FROM nursescheduling WHERE NurseID = ? AND Date = ? AND Time = ?");
        $checkExistingSchedule->bind_param("sss", $nurseName, $dateToReserve, $timeToReserve);
        $checkExistingSchedule->execute();
        $checkExistingScheduleResult = $checkExistingSchedule->get_result();

        if ($checkExistingScheduleResult->num_rows > 0) {
            $message = "You are already scheduled for this date and time.";
        } else {
            // Update reservation without safety checks
            $updateReservation = $conn->prepare("UPDATE nursescheduling SET NurseID = ? WHERE NurseSchedulingID = ?");
            $updateReservation->bind_param("ss", $nurseName, $nurseSchedulingID);

                if ($updateReservation->execute()) {

                // Check if the nurse is already scheduled for the selected Date and Time
                $checkExistingSchedule = $conn->prepare("SELECT COUNT(SchedulingID) as count FROM vaccinationschedule");
                $checkExistingSchedule->execute();
                $checkExistingScheduleResult = $checkExistingSchedule->get_result()->fetch_assoc();

                $count = $checkExistingScheduleResult['count'] + 1;
                // Insert into vaccinationschedule 10 times
                $insertIntoVaccinationSchedule = $conn->prepare("INSERT INTO vaccinationschedule (SchedulingID, VaccinationID, NurseID, Date, Time) VALUES (?, 'Moderna', ?, ?, ?)");
                $insertIntoVaccinationSchedule->bind_param("ssss", $count, $nurseName, $dateToReserve, $timeToReserve);
                for ($i = 0; $i < 10; $i++) {
                    if ($insertIntoVaccinationSchedule->execute()) {
                        $message = "You have successfully reserved your appointment!";
                    } else {
                        $message = "Error during reservation: " . $conn->error;
                    }
                    $count = $count + 1; // Update the count
                    $insertIntoVaccinationSchedule->bind_param("ssss", $count, $nurseName,$dateToReserve, $timeToReserve);
                }
                
                $insertIntoVaccinationSchedule->close();
            } else {
                $message = "Error during reservation: " . $conn->error;
            }

            $updateReservation->close();
        }

        $checkExistingSchedule->close();
    }
}

// Query to retrieve available slots
$sql = "SELECT NurseSchedulingID, VaccineName, Date, Time FROM nursescheduling WHERE NurseID IS NULL";
$result = $conn->query($sql);


// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nurse Scheduling</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
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
    <h1 class="mb-4 text-center">Nurse Scheduling</h1>

    <!-- Display success or error message -->
    <?php if (!empty($message)): ?>
        <div class="alert alert-<?php echo (strpos($message, 'successfully') !== false) ? 'success' : 'danger'; ?>" role="alert">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>

    <!-- Display the schedule in a form -->
    <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <form class="schedule-form" form method="POST" action="nurseScheduling.php">
                <table class="table">
                    <thead>
                    <tr>
                        <th>NurseSchedulingID</th>
                        <th>VaccinationName</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td><?php echo $row['NurseSchedulingID']; ?></td>
                        <td><?php echo $row['VaccineName']; ?></td>
                        <td><?php echo $row['Date']; ?></td>
                        <td><?php echo $row['Time']; ?></td>
                        <td>
                            <input type="hidden" name="schedulingID" value="<?php echo $row['NurseSchedulingID']; ?>">
                            <input type="hidden" name="reserve_date" value="<?php echo $row['Date']; ?>">
                            <input type="hidden" name="reserve_time" value="<?php echo $row['Time']; ?>">
                            <button type="submit" class="btn btn-primary">Reserve</button>
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
    <a href="nurseDashboard.php" class="btn btn-secondary">Back</a>
</div>

<!-- Bootstrap JS and dependencies -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

</body>
</html>
