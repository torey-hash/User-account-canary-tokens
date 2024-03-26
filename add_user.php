
<?php


session_start();
if(!isset($_SESSION["loggedin"]) ){
  header("location:index.php");
}
// Database configuration
$host = 'localhost';
$dbname = 'canary_tokens';
$username = 'root';
$password = '';

// Connect to the database
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Could not connect to the database $dbname :" . $e->getMessage());
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and validate form data
    $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password
    $is_decoy = filter_var($_POST['is_decoy'], FILTER_VALIDATE_BOOLEAN);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

    // Insert data into the database
    $sql = "INSERT INTO users (username, password, is_decoy, email) VALUES (:username, :password, :is_decoy, :email)";
    $stmt = $pdo->prepare($sql);

    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $password);
    $stmt->bindParam(':is_decoy', $is_decoy, PDO::PARAM_BOOL);
    $stmt->bindParam(':email', $email);

    if ($stmt->execute()) {
        echo "User successfully added!";
    } else {
        echo "Error adding user.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insert User</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #1c2230;
            font-family: 'Lato', sans-serif;
            color: #ffffff;
        }
        .container {
            background: #2a2f45;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
            margin-top: 20px;
        }
        .form-control, .form-control:focus, .custom-select {
            background-color: #2a2f45;
            border-color: #50597b;
            color: #ffffff;
        }
        .form-control::placeholder {
            color: #b1b1b3;
        }
        .btn-primary {
            background-color: #4e5d78;
            border-color: #4e5d78;
        }
        .btn-primary:hover {
            background-color: #3e4e68;
            border-color: #3e4e68;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="text-center">Insert User</h2>
        <form method="post" action="add_user.php">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="is_decoy">Is Decoy:</label>
                <select class="custom-select" id="is_decoy" name="is_decoy">
                    <option value="0">No</option>
                    <option value="1">Yes</option>
                </select>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <button type="submit" class="btn btn-success">Insert User</button>
           
        </form>
        <a href="dashboard.php" class="btn btn-secondary mt-2">Go Back</a> 
        
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
