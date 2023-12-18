<?php include('server.php') ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UI Health Vaccination System</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../style.css">
</head>
<body>

<div class="container mt-5">
    <h1 class="mb-4 text-center">UI Health Vaccination System</h1>

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
        
    <!-- Login Form -->
    <form id="loginForm" form method="POST" action="index.php">
        <div class="form-group">
            <label for="username">Username:</label>
            <input type="text" class="form-control" id="username" name = "username" required>
        </div>
        <div class="form-group"> 
            <label for="password">Password:</label>
            <input type="password" class="form-control" id="password" name = "password" required>
        </div>
        <div class="form-group">
            <label for="userType">Select User Type:</label>
            <select class="form-control" id="userType" name = "usertype" required>
                <option value="admin">Admin</option>
                <option value="nurse">Nurse</option>
                <option value="patient">Patient</option>
            </select>
        </div>
            <button type="submit" name="login_user" class="btn btn-primary mt-4 mr-4">Login</button>
            <p id = "register">
                Not yet a member? <a href="../Patient/register_patient.php">Sign up</a>
            </p>
        </div>
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
