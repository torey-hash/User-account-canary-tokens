<?php
// Initialize the session
session_start();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>

    <!--Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #003973, #E5E5BE, #2E3192, #1BFFFF);
            background-size: 400% 400%;
            animation: gradientBG 15s ease infinite;
            color: #ffffff; /* White text */
        }
        @keyframes gradientBG {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        .card {
            background-color: rgba(28, 29, 44, 0.9);
            backdrop-filter: blur(10px);
            border: 1px solid #2E3192;
        }
        .btn-primary {
            background-color: #1BFFFF;
            border: none;
        }
        .form-control {
            background-color: rgba(38, 38, 56, 0.9);
            color: #ffffff;
            border: 1px solid #2E3192;
        }
        .form-control:focus {
            border-color: #1BFFFF;
            box-shadow: 0 0 0 0.2rem rgba(27, 255, 255, 0.25);
        }
        .card-title {
            color: #E5E5BE;
        }
        /* Custom styles for the toggle password eye */
        .toggle-password {
            cursor: pointer;
            position: absolute;
            right: 10px;
            top: 10px;
            color: #E5E5BE;
        }
    </style>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
</head>
<body>
    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title text-center">Login</h5>
                        <form action="login.php" method="post">
                            <div class="form-group">
                                <label for="username">Username:</label>
                                <input type="text" class="form-control" id="username" name="username" required>
                            </div>
                            <div class="form-group position-relative">
                                <label for="password">Password:</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                                <span toggle="#password" class="fa fa-fw fa-eye-slash field-icon toggle-password"></span>


                            </div>
                            <div class="text-center">
                                <input type="submit" class="btn btn-primary" value="Login">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and its dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- Font Awesome for the eye icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <script>
    // JavaScript to toggle the password visibility
    document.querySelector('.toggle-password').addEventListener('click', function (e) {
        // toggle the type attribute
        const target = document.getElementById('password');
        // logic
        const type = target.getAttribute('type') === 'text' ? 'password' : 'text';
        target.setAttribute('type', type);
        
        this.classList.toggle('fa-eye'); // If password is shown, this will be added for hiding next.
        this.classList.toggle('fa-eye-slash'); // If password is hidden, this will be added for showing next.
    });
</script>


</body>
</html>
