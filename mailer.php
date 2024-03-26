<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

function sendMail($to, $subject, $body) {
    $mail = new PHPMailer(true); // Passing `true` enables exceptions
    try {
        // Server settings
        $mail->isSMTP(); // Set mailer to use SMTP
        $mail->Host = 'smtp.gmail.com'; // Specify main and backup SMTP servers
        $mail->SMTPAuth = true; // Enable SMTP authentication
        $mail->Username = 'pinkyvicky622@gmail.com'; // SMTP username
        $mail->Password = 'pmph dume yksr rwju'; // SMTP password
        $mail->SMTPSecure = 'tls'; // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587; // TCP port to connect to

        // Recipients
        $mail->setFrom('from@example.com', 'Mailer');
        $mail->addAddress($to); // Add a recipient

        // Content
        $mail->isHTML(true); // Set email format to HTML
        $mail->Subject = $subject;
        $mail->Body = $body;

        $mail->send();
        echo 'Message has been sent';
    } catch (Exception $e) {
        echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
    }
}
// Assuming $attemptedUsername and $clientIP are captured at the login attempt
$attemptedUsername = $_POST['username']; // Ensure you sanitize this input
$clientIP = $_SERVER['REMOTE_ADDR'];

// Customize the email content
$subject = 'Alert Email for Login Attempt';
$body = "<h1>Alert Email</h1><p>There has been a login attempt with username: <strong>{$attemptedUsername}</strong> from IP: <strong>{$clientIP}</strong>.</p>";

// Example usage
sendMail('nimotori74@gmail.com', $subject, $body);
