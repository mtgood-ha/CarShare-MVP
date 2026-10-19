<?php
    include 'dbconn.php';
    session_start();
    $VolunteerName = isset($_SESSION['name']) ? $_SESSION['name'] : 'Driver';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="styleV.css">
</head>
<body>
    
    <div class="center-wrapper">
        <h2>Welcome, <?php echo htmlspecialchars($VolunteerName); ?></h2>
        
        <div class="driverMENU">
            <a href="ManageAccount.php" class="baseBtn whiteBtn" style="width: 100%; box-sizing: border-box;">Manage Account</a>
            <a href="RewardPage.php" class="baseBtn whiteBtn" style="width: 100%; box-sizing: border-box;">Reward Page</a>
            <a href="ViewEventPage.php" class="baseBtn whiteBtn" style="width: 100%; box-sizing: border-box;">View Event Page</a>
            <a href="RewardPointLeaderboard.php" class="baseBtn whiteBtn" style="width: 100%; box-sizing: border-box;">View Leaderboard</a>
            <a href="ViewNearbyPassengers.php" class="baseBtn whiteBtn" style="width: 100%; box-sizing: border-box;">View Nearby APU Student Passengers</a>
            
            <br>
            <a style="color: darkblue;" href="PopUpRequestFromPassenger.php">(View New Ride Request)</a>
        </div>
        
        <br>
    <button onclick="window.location.href='logout.php';" class="baseBtn">Logout</button>    
</div>
</body>
</html>