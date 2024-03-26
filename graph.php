<?php
// Database connection
require_once('db.php'); // Ensure this path points to your database connection script
session_start();
if(!isset($_SESSION["loggedin"]) ){
  header("location:index.php");
}
// Fetch unsuccessful login attempt counts per day
$queryUnsuccessfulAttempts = "SELECT DATE(attempt_time) AS attempt_date, COUNT(*) AS attempt_count FROM login_attempts WHERE success = 0 GROUP BY DATE(attempt_time)";
$stmtUnsuccessfulAttempts = $pdo->query($queryUnsuccessfulAttempts);
$unsuccessfulAttemptsData = $stmtUnsuccessfulAttempts->fetchAll(PDO::FETCH_ASSOC);

// Fetch successful login attempt counts per day
$querySuccessfulAttempts = "SELECT DATE(attempt_time) AS attempt_date, COUNT(*) AS attempt_count FROM login_attempts WHERE success = 1 GROUP BY DATE(attempt_time)";
$stmtSuccessfulAttempts = $pdo->query($querySuccessfulAttempts);
$successfulAttemptsData = $stmtSuccessfulAttempts->fetchAll(PDO::FETCH_ASSOC);

// Fetch decoy access counts per decoy name
$queryDecoyAccesses = "SELECT users.username AS decoy_name, COUNT(*) AS access_count FROM access_logs JOIN users ON access_logs.user_id = users.id WHERE users.is_decoy = 1 GROUP BY users.username";
$stmtDecoyAccesses = $pdo->query($queryDecoyAccesses);
$decoyAccessesData = $stmtDecoyAccesses->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
<style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background: #f4f4f4;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }
        .chart-container {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            background: #ffffff;
            border-radius: 8px;
            margin: 20px;
            padding: 15px;
            width: 90%;
            max-width: 800px;
        }
        h2 {
            text-align: center;
            color: #333;
        }
    </style>
    <meta charset="UTF-8">
    <title>Dashboard Metrics</title>
    <link rel="stylesheet" href="dashboard.css"> <!-- Assuming you have a CSS file for styling -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

<a href="dashboard.php" class="btn btn-primary mt-2">Go Back</a> 

    <!-- Unsuccessful Login Attempts Graph Container -->
    <div class="chart-container" style="position: relative; height:40vh; width:80vw">
        <canvas id="unsuccessfulLoginAttemptsChart"></canvas>
    </div>
    
    <!-- Successful Login Attempts Graph Container -->
    <div class="chart-container" style="position: relative; height:40vh; width:80vw; margin-top: 50px;">
        <canvas id="successfulLoginAttemptsChart"></canvas>
    </div>

    <!-- Decoy Accesses Graph Container -->
    <div class="chart-container" style="position: relative; height:40vh; width:80vw; margin-top: 50px;">
        <canvas id="decoyAccessesChart"></canvas>
    </div>

    <script>
    document.addEventListener("DOMContentLoaded", function(event) {
        // Unsuccessful Login Attempts Chart
        var ctxUnsuccessful = document.getElementById('unsuccessfulLoginAttemptsChart').getContext('2d');
        var unsuccessfulLoginAttemptsChart = new Chart(ctxUnsuccessful, {
            type: 'line',
            data: {
                labels: [<?php foreach($unsuccessfulAttemptsData as $row) { echo '"' . $row['attempt_date'] . '",'; } ?>],
                datasets: [{
                    label: 'Unsuccessful Login Attempts',
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    data: [<?php foreach($unsuccessfulAttemptsData as $row) { echo $row['attempt_count'] . ','; } ?>],
                    fill: false,
                }]
            },
            options: chartOptions('Unsuccessful Login Attempts per Day', 'Date', 'Number of Attempts')
        });

        // Successful Login Attempts Chart
        var ctxSuccessful = document.getElementById('successfulLoginAttemptsChart').getContext('2d');
        var successfulLoginAttemptsChart = new Chart(ctxSuccessful, {
            type: 'line',
            data: {
                labels: [<?php foreach($successfulAttemptsData as $row) { echo '"' . $row['attempt_date'] . '",'; } ?>],
                datasets: [{
                    label: 'Successful Login Attempts',
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    data: [<?php foreach($successfulAttemptsData as $row) { echo $row['attempt_count'] . ','; } ?>],
                    fill: false,
                }]
            },
            options: chartOptions('Successful Login Attempts per Day', 'Date', 'Number of Attempts')
        });

        // Decoy Accesses Chart
        var ctxDecoy = document.getElementById('decoyAccessesChart').getContext('2d');
        var decoyAccessesChart = new Chart(ctxDecoy, {
            type: 'bar',
            data: {
                labels: [<?php foreach($decoyAccessesData as $row) { echo '"' . $row['decoy_name'] . '",'; } ?>],
                datasets: [{
                    label: 'Decoy Access Counts',
                    backgroundColor: 'rgba(153, 102, 255, 0.2)',
                    borderColor: 'rgba(153, 102, 255, 1)',
                    data: [<?php foreach($decoyAccessesData as $row) { echo $row['access_count'] . ','; } ?>],
                    fill: false,
                }]
            },
            options: chartOptions('Decoy Access Counts by Name', 'Decoy Name', 'Number of Accesses')
        });

        function chartOptions(title, xLabel, yLabel) {
            return {
                responsive: true,
                title: {
                    display: true,
                    text: title,
                    fontSize: 20
                },
                scales: {
                    yAxes: [{
                        scaleLabel: {
                            display: true,
                            labelString: yLabel
                        },
                        ticks: {
                            beginAtZero: true
                        }
                    }],
                    xAxes: [{
                        scaleLabel: {
                            display: true,
                            labelString: xLabel
                        }
                    }]
                }
            };
        }
    });
    </script>
</body>
</html>
