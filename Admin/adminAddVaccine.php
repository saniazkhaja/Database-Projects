<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Vaccine</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>

    <div class="container mt-5">
        <h1 class="mb-4 text-center">Add Vaccine</h1>

        <?php
        // Include the logic file
        include('adminAddVaccineLogic.php');
        ?>

        <form method='POST' action='adminAddVaccineLogic.php'>
            <div class="form-group">
                <label for="vaccinationName">Vaccination Name:</label>
                <input type="text" class="form-control" name="vaccinationName" required>
            </div>
            <div class="form-group">
                <label for="companyName">Company Name:</label>
                <input type="text" class="form-control" name="companyName" required>
            </div>
            <div class="form-group">
                <label for="requiredDoses">Required Doses:</label>
                <input type="number" class="form-control" name="requiredDoses" required>
            </div>
            <div class="form-group">
                <label for="totalQuantity">Total Quantity:</label>
                <input type="number" class="form-control" name="totalQuantity" required>
            </div>
            <div class="form-group">
                <label for="holdAmt">Hold Amount:</label>
                <input type="number" class="form-control" name="holdAmt" required>
            </div>
            <div class="form-group">
                <label for="optionalText">Optional Text:</label>
                <input type="text" class="form-control" name="optionalText">
            </div>
            <button type='submit' class='btn btn-primary' name='saveChanges'>Save Changes</button>
        </form>

        <!-- Add a link to navigate back to the admin dashboard -->
        <a href="../Admin/adminDashboard.php" class="btn btn-primary mt-3">Back to Admin Dashboard</a>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

</body>
</html>
