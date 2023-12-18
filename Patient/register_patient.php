<?php include('register.php') ?>

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

    <div class="container mt-5">
        <h1 class="mb-4 text-center">Patient Registration</h1>

           <!-- Display Errors -->
        <?php if (count($errors) > 0): ?>
            <div class="alert alert-danger">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo $error; ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

            <!-- Display Successful Registration -->
        <?php if (count($success) > 0): ?>
            <div class="alert alert-sucess">
                <ul> 
                    <?php foreach ($success as $sucess): ?>
                        <li><?php echo $sucess; ?></li> 
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
    
        <!-- Patient Registration Form -->
        <form id="patientRegistrationForm" form method = "POST"  action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
            <!-- First Name -->
            <div class="form-group">
                <label for="fname">First Name:</label>
                <input type="text" class="form-control" id="fname" name = "fname" required>
            </div>
    
            <!-- Middle Initial -->
            <div class="form-group">
                <label for="mi">Middle Initial:</label>
                <input type="text" class="form-control" id="mi" name = "mi">
            </div>
    
            <!-- Last Name -->
            <div class="form-group">
                <label for="lname">Last Name:</label>
                <input type="text" class="form-control" id="lname" name = "lname" required>
            </div>
    
            <!-- SSN -->
            <div class="form-group">
                <label for="ssn">Social Security Number:</label>
                <input type="text" class="form-control" id="ssn" name = "ssn" required>
            </div>
    
            <!-- Age -->
            <div class="form-group">
                <label for="age">Age:</label>
                <input type="number" class="form-control" id="age" name = "age" required>
            </div>
    
            <!-- Gender -->
            <div class="form-group">
                <label for="gender">Gender:</label>
                <select class="form-control" id="gender" name = "gender" required>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                    <option value="other">Other</option>
                </select>
            </div>
    
            <!-- Race -->
            <div class="form-group">
                <label for="race">Race:</label>
                <input type="text" class="form-control" id="race" name = "race" required>
            </div>
    
            <!-- Occupation -->
            <div class="form-group">
                <label for="occupation">Occupation:</label>
                <input type="text" class="form-control" id="occupation" name = "occupation" required>
            </div>
    
            <!-- Medical History -->
            <div class="form-group">
                <label for="medicalHistory">Medical History:</label>
                <textarea class="form-control" id="medicalHistory" rows="3" name = "medicalHistory" required></textarea>
            </div>
    
            <!-- Phone -->
            <div class="form-group">
                <label for="phone">Phone:</label>
                <input type="tel" class="form-control" id="phone" name = "phone" required>
            </div>
    
            <!-- Address -->
            <div class="form-group">
                <label for="address">Address:</label>
                <input type="text" class="form-control" id="address" name = "address" required>
            </div>

            <!-- Username -->
            <div class="form-group">
                <label for="username">Create a Username:</label>
                <input type="text" class="form-control" id="username" name = "username" required>
            </div>

            <!-- Password -->
            <div class="form-group">
                <label for="password">Create a password:</label>
                <input type="password" class="form-control" id="password" name = "password" required>
            </div>

            <!-- Confirm Password -->
            <div class="form-group">
                <label for="confirmPassword">Confirm password:</label>
                <input type="password" class="form-control" id="confirmPassword" name = "confirmPassword" required>
            </div>
    
            <!-- Patient Registration Submit Button -->
            <button type="submit" class="btn btn-primary" name="submitPatientRegistration">Submit Registration</button>
            <p id = "login">
                Would you like to log in now? <a href="../Login/index.php">Log in</a>
            </p>
        </form>
    </div>
    
<!-- Bootstrap JS and dependencies -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
<!-- Custom JavaScript -->
<script src="../app.js"></script>

</body>
</html>
