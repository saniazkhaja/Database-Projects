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
    <script> 
        function redirectToDeleteNurse() {
            window.location.href = '../Admin/deleteNurse.php';
        }

        function redirectToRegisterNurse() {
            window.location.href = '../Admin/registerNurse.php';
        }

        function redirectToUpdateAdminNurseInfo() {
            window.location.href = '../Admin/updateAdminNurseInfo.php';
        }

        function redirectToViewAdminNurseInfo() {
            window.location.href = '../Admin/viewAdminNurseInfo.php';
        }

        function redirectToViewAdminPatientInfo() {
            window.location.href = '../Admin/viewAdminPatientInfo.php';
        }

        function redirectToLoginPage() {
            window.location.href = '../Login/index.php';
        }

        function redirectToAdminAddVaccine() {
            window.location.href = '../Admin/adminAddVaccine.php';
        }

        function redirectToAdminUpdateVaccine() {
            window.location.href = '../Admin/adminUpdateVaccine.php';
        }

    </script>

     <!-- Admin UI -->
     <div class="container mt-5">
        <button type="button" class="btn btn-primary" onclick="redirectToLoginPage()">Go Back to Login Page</button>
        <!-- Admin-specific UI elements go here -->
        <h2 class="mb-4 text-center">Admin Dashboard</h2>

        <!-- Nurse Management Section -->
        <h3 class="mb-3">Nurse Management</h3>
        <div class="row">
            <!-- Functionality 1: Register a Nurse -->
            <div class="col-md-4">
                <div class="card mb-4 text-center">
                    <div class="card-body">
                        <h4 class="card-title">Register a Nurse</h4>
                        <form id="registerNurseForm">
                            <!-- Nurse details input fields go here -->
                            <button type="button" class="btn btn-primary" onclick="redirectToRegisterNurse()">Register Nurse</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Functionality 2: Update Nurse Info -->
            <div class="col-md-4">
                <div class="card mb-4 text-center">
                    <div class="card-body">
                        <h4 class="card-title">Update Nurse Info</h4>
                        <form id="updateNurseForm">
                            <!-- Nurse selection dropdown and updated info input fields go here -->
                            <button type="button" class="btn btn-primary" onclick="redirectToUpdateAdminNurseInfo()">Update Nurse Info</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Functionality 3: Delete a Nurse -->
            <div class="col-md-4">
                <div class="card mb-4 text-center">
                    <div class="card-body">
                        <h4 class="card-title">Delete a Nurse</h4>
                        <form id="deleteNurseForm">
                            <!-- Nurse selection dropdown goes here -->
                            <button type="button" class="btn btn-danger" onclick="redirectToDeleteNurse()">Delete Nurse</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Information Viewing Section -->
        <h3 class="mb-3 mt-4">Information Viewing</h3>
        <div class="row">
            <!-- Functionality 6: View Nurse Info -->
            <div class="col-md-6">
                <div class="card mb-4 text-center">
                    <div class="card-body">
                        <h4 class="card-title">View Nurse Info</h4>
                        <button type="button" class="btn btn-info" onclick="redirectToViewAdminNurseInfo()">View Nurse Info</button>
                    </div>
                </div>
            </div>

            <!-- Functionality 7: View Patient Info -->
            <div class="col-md-6">
                <div class="card mb-4 text-center">
                    <div class="card-body">
                        <h4 class="card-title">View Patient Info</h4>
                        <button type="button" class="btn btn-info" onclick="redirectToViewAdminPatientInfo()">View Patient Info</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Vaccine Management Section -->
        <h3 class="mb-3 mt-4">Vaccine Management</h3>
        <div class="row">
            <!-- Functionality 4: Add Vaccine -->
            <div class="col-md-6">
                <div class="card mb-4 text-center">
                    <div class="card-body">
                        <h4 class="card-title">Add Vaccine</h4>
                        <form id="addVaccineForm">
                            <!-- Vaccine details input fields go here -->
                            <button type="button" class="btn btn-success" onclick="redirectToAdminAddVaccine()">Add Vaccine</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Functionality 5: Update Vaccine -->
            <div class="col-md-6">
                <div class="card mb-4 text-center">
                    <div class="card-body">
                        <h4 class="card-title">Update Vaccine</h4>
                        <form id="updateVaccineForm">
                            <!-- Vaccine selection dropdown and updated info input fields go here -->
                            <button type="button" class="btn btn-success" onclick="redirectToAdminUpdateVaccine()">Update Vaccine</button>
                        </form>
                    </div>
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
