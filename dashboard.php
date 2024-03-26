<?php
require_once('db.php');
session_start();
if(!isset($_SESSION["loggedin"]) ){
  header("location:index.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
    <style>
        body {
            margin: 0;
            padding: 0px;
            background-color: #1c2230;
            font-family: 'Lato', sans-serif;
            color: #ffffff;
        }
        .container {
            background: #2a2f45;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
            margin-bottom: 20px;
        }
        .navbar, .modal-header, .modal-footer {
            background-color: #162237;
        }
        .navbar a, .modal-title {
            color: #c6d5f9;
        }
        .btn-outline-danger, .btn-outline-secondary {
            color: #fff;
        }
        .btn-outline-danger:hover, .btn-outline-secondary:hover {
            color: #fff;
        }
        .table-responsive {
            background-color: #fff;
            border-radius: 5px;
            padding: 20px;
        }
        .table {
            color: #000;
        }
        .clickable-card {
            cursor: pointer;
        }
    </style>
</head>
<body>
    <?php
    $host = 'localhost'; // or your host
    $dbname = 'canary_tokens';
    $username = 'root';
    $password = '';

    
    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch(PDOException $e) {
        die("ERROR: Could not connect. " . $e->getMessage());
    }

    // Initialize variables
    $showAttempts = false;
    $showUsers = false;
    $showAccessLogs = false; 

    // Check what to display based on GET parameter
    if (isset($_GET['view'])) {
        switch ($_GET['view']) {
            case 'attempts':
                $query = "SELECT id, username, attempt_time, ip_address, success FROM login_attempts";
                $stmt = $pdo->query($query);
                $attempts = $stmt->fetchAll();
                $showAttempts = true;
                break;
            case 'users':
                $queryUsers = "SELECT id, username, password, is_decoy, email FROM users";
                $stmtUsers = $pdo->query($queryUsers);
                $users = $stmtUsers->fetchAll();
                $showUsers = true;
                break;
            case 'access_logs':
                    $queryAccessLogs = "SELECT al.id, u.username, al.ip_address, al.access_time FROM access_logs al JOIN users u ON al.user_id = u.id";
                    $stmtAccessLogs = $pdo->query($queryAccessLogs);
                    $accessLogs = $stmtAccessLogs->fetchAll();
                    $showAccessLogs = true;
                    break;
        }
        
    }
    ?>

    <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
        <a class="navbar-brand" href="dashboard.php">Welcome to your Dashboard <?php echo($_SESSION["username"]); ?></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="add_user.php">Add Users/Decoy</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="graph.php">See graph</a>
                </li>
                <li class="nav-item">
                    <a class="btn btn-outline-danger" href="logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container mt-4">
        <!-- Login Attempts Card -->
        <div class="card text-white bg-primary mb-3 clickable-card" onclick="location.href='?view=attempts';">
            <div class="card-header">Login Attempts Summary</div>
            <div class="card-body">
                <h5 class="card-title">Click to view login attempts</h5>
            </div>
        </div>

        <!-- Users Card -->
        <div class="card text-white bg-success mb-3 clickable-card" onclick="location.href='?view=users';">
            <div class="card-header">Users</div>
            <div class="card-body">
                <h5 class="card-title">Click to view all users</h5>
            </div>
        </div>

 <!-- Access logs Card -->
        <div class="card text-white bg-info mb-3 clickable-card" onclick="location.href='?view=access_logs';">
    <div class="card-header">Decoy Access Logs</div>
    <div class="card-body">
        <h5 class="card-title">Click to view access logs</h5>
    </div>
</div>


        <!-- Login Attempts Table -->
        <?php if ($showAttempts): ?>
            <div class="container">
    <h2>Login Attempts</h2>
        <div class="table-responsive">
        
            <table class="table table-bordered table-hover">
                <thead class="thead-dark">
                    
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Attempt Time</th>
                        <th>IP Address </th>
                        <th>Success</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($attempts as $attempt): ?>
                        
                        <tr>
                            <td><?= htmlspecialchars($attempt['id']) ?></td>
                            <td><?= htmlspecialchars($attempt['username']) ?></td>
                            <td><?= htmlspecialchars($attempt['attempt_time']) ?></td>
                            <td><?= htmlspecialchars($attempt['ip_address']) ?></td>
                            <td><?= htmlspecialchars($attempt['success']) ? 'Yes' : 'No' ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>

        <!-- Users Table -->
        <?php if ($showUsers): ?>
            <div class="container">
    <h2>Users</h2>
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                
                        <th>Is Decoy</th>
                        <th>Email</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?= htmlspecialchars($user['id']) ?></td>
                            <td><?= htmlspecialchars($user['username']) ?></td>
                          
                            <td><?= htmlspecialchars($user['is_decoy']) ? 'Yes' : 'No' ?></td>
                            <td><?= htmlspecialchars($user['email']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>

 <?php if ($showAccessLogs): ?>
<div class="container">
    <h2>Decoy Access Logs</h2>
    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Decoy username</th>
                    <th>IP Address used to access decoy</th>
                    <th>Access Time</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($accessLogs as $log): ?>
                <tr>
                    <td><?= htmlspecialchars($log['id']) ?></td>
                    <td><?= htmlspecialchars($log['username']) ?></td>
                    <td><?= htmlspecialchars($log['ip_address']) ?></td>
                    <td><?= htmlspecialchars($log['access_time']) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif; ?>

    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
