<?php
    include 'dbconn.php';
    session_start();
    $PassengerName = isset($_SESSION['name']) ? $_SESSION['name'] : 'Passenger';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Passenger Dashboard</title>
    <link rel="stylesheet" href="styleP.css">
</head>
<body>
    
    <div class="dashboard-container">
        <h2>Welcome, <?php echo htmlspecialchars($PassengerName); ?></h2>
        
        <div class="dashboard-menu">
            <a href="PersonalDetailsP.php" class="dashboard-btn">Personal Details</a>
            <a href="ChooseAMethod.html" class="dashboard-btn">Request Ride</a>
            <a href="RideHistoryP.php" class="dashboard-btn">View Ride History</a>
        </div>
        
        <a href="index.php" class="dashboard-logout">Logout</a>
    </div>

</body>
</html>