<?php
require 'db.php';
require 'mailer.php';

session_start();
if(!isset($_SESSION["loggedin"]) ){
  header("location:index.php");
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $ipAddress = $_SERVER['REMOTE_ADDR'];
    $success = 0; // Default to unsuccessful attempt

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $success = 1; // Mark as successful attempt
        if ($user['is_decoy']) {
            // Log decoy access
            $stmt = $pdo->prepare("INSERT INTO access_logs (user_id, ip_address) VALUES (?, ?)");
            $stmt->execute([$user['id'], $ipAddress]);

            // Send email alert
            sendMail('nimotori74@gmail.com', 'Decoy Account Accessed', "Alert: Decoy account:$username accessed by IP: $ipAddress.");
            header("Location:redirect.php");
        } else {
            session_start();
                        
                        // Store data in session variables
                        $_SESSION["loggedin"] = true;
                        $_SESSION["id"] = $user['id'];

                        $_SESSION["username"] = $username;
           
            header("Location:dashboard.php");
        }
    } else {
        header("Location:redirect.php");
    }

    // Log the login attempt regardless of success
    $stmt = $pdo->prepare("INSERT INTO login_attempts (username, ip_address, success) VALUES (?, ?, ?)");
    $stmt->execute([$username, $ipAddress, $success]);

    echo $success ? "Login successful." : "Login failed.";
}
