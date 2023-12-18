<!-- register_patient.html -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Registration</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../style.css">
    <script src="../app.js"></script>
</head>
<body>

 <!-- Nurse UI -->
 <div class="container mt-5">
    <button type="button" class="btn btn-primary" onclick="redirectToLoginPage()">Go Back to Login Page</button>
    <!-- Nurse-specific UI elements go here -->
    <h2 class="mb-4 text-center">Nurse Dashboard</h2>

    <!-- Nurse Management Section -->
    <h3 class="mb-3 mt-4">Time Management</h3>
    <div class="card-deck mb-4">
        <!-- Functionality 9: Schedule Time -->
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Schedule Time</h4>
                <form id="scheduleTimeForm">
                    <!-- Schedule time input fields go here -->
                    <button type="button" class="btn btn-success" onclick = "redirectToNurseScheduling()">Schedule Time</button>
                </form>
            </div>
        </div>

        <!-- Functionality 10: Cancel a Time -->
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">View/Cancel a Time</h4>
                <form id="cancelTimeForm">
                    <!-- Cancel time input fields go here -->
                    <button type="button" class="btn btn-danger" onclick="redirectToDeleteNurseSchedule()">View/Cancel Time</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function redirectToViewNurseInfo() {
            window.location.href = '../Nurse/viewNurseInfo.php';
        }

        function redirectToUpdateNurseInfo() {
            window.location.href = '../Nurse/updateNurseInfo.php';
        }

        function redirectToNurseRecordVaccination() {
            window.location.href = '../Nurse/nurseRecordVaccination.php';
        }

        function redirectToDeleteNurseSchedule() {
            window.location.href = '../Nurse/deleteNurseSchedule.php';
        }

        function redirectToLoginPage() {
            window.location.href = '../Login/index.php';
        }
    </script>

    <!-- Information Viewing Section -->
    <h3 class="mb-3 mt-4">Information</h3>
    <div class="card-deck mb-4">
        <!-- Functionality 11: View Information -->
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">View Information</h4>
                <button type="button" class="btn btn-info" onclick = "redirectToViewNurseInfo()">View Information</button>
            </div>
        </div>
        <!-- Functionality 8: Update Information -->
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Update Information</h4>
                <form id="updateNurseInfoForm">
                    <!-- Update information input fields go here -->
                    <button type="button" class="btn btn-info" onclick = "redirectToUpdateNurseInfo()">Update Information</button>
                </form>
            </div>
        </div>
    </div>
        
    <!-- Vaccine Management Section -->
    <h3 class="mb-3 mt-4">Vaccine Management</h3>
    <div class="card-deck mb-4">
        <!-- Functionality 12: Vaccination -->
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Vaccination</h4>
                <form id="recordVaccinationForm">
                    <!-- Vaccination input fields go here -->
                    <button type="button" class="btn btn-primary" onclick="redirectToNurseRecordVaccination()">Record Vaccination</button>
                </form>
            </div>
        </div>
    </div>
</div>
    
<!-- Bootstrap JS and dependencies -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
<!-- Custom JavaScript -->
<script src="../app.js"></script>

</body>
</html>
