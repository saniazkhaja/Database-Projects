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
</head>
<body>

  <!-- Patient UI -->
  <div class="container mt-5">
    <button type="button" class="btn btn-primary" onclick="redirectToLoginPage()">Go Back to Login Page</button>
    <!-- Patient-specific UI elements go here -->
    <h2 class="mb-4 text-center">Patient Dashboard</h2>

    <!-- Patient Management Section -->
    <h3 class="mb-3 mt-4">Appointment Management</h3>
    <div class="card-deck mb-4">
        <!-- Functionality 15: Schedule a Vaccination Time -->
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Schedule a Vaccination Time</h4>
                <form id="scheduleVaccinationTimeForm">
                    <!-- Schedule vaccination time input fields go here -->
                    <button type="button" class="btn btn-success" onclick="redirectToVaccineSchedule()">Schedule Vaccination Time</button>
                </form>
            </div>
        </div>

        <!-- Functionality 16: Cancel Schedule -->
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">View/Cancel Schedule</h4>
                <form id="cancelScheduleForm">
                    <!-- Cancel schedule input fields go here -->
                    <button type="button" class="btn btn-danger" onclick="redirectToCancelSchedule()">View/Cancel Schedule</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Information Viewing Section -->
    <h3 class="mb-3 mt-4">Information</h3>
    <div class="card-deck mb-4">
        <!-- Functionality 17: View Information -->
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">View Information</h4>
                <button type="button" class="btn btn-info" onclick="redirectToViewPatientInfo()">View Information</button>
            </div>
        </div>


        <!-- Functionality 14: Update Info -->
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Update Information</h4>
                <form id="updatePatientInfoForm">
                    <!-- Update information input fields go here -->
                    <button type="button" class="btn btn-info" onclick="redirectToUpdatePatientInfo()">Update Information</button>
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
