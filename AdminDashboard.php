<?php
session_start();
include 'dbconn.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 3) {
    header("Location: LoginPage.html");
    exit();
}

$totalRides = 0;
$volunteerDrivers = 0;
$passengers = 0;
$ongoingReports = 0;

$result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM rides");
if ($result) {
    $row = mysqli_fetch_assoc($result);
    $totalRides = $row['total'];
}

$result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM user WHERE RoleID = 1");
if ($result) {
    $row = mysqli_fetch_assoc($result);
    $volunteerDrivers = $row['total'];
}

$result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM user WHERE RoleID = 2");
if ($result) {
    $row = mysqli_fetch_assoc($result);
    $passengers = $row['total'];
}

$result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM report WHERE ReportStatus = 'Ongoing'");
if ($result) {
    $row = mysqli_fetch_assoc($result);
    $ongoingReports = $row['total'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="styleA.css">
</head>
<body>
    <div class="center-wrapper">

        <h1>Admin Dashboard</h1>

        <div class="stats-wrapper">

            <div class="stat-box">
                <h3>Total Rides</h3>
                <p class="stat-number"><?php echo $totalRides; ?></p>
            </div>

            <div class="stat-box">
                <h3>Volunteer Drivers</h3>
                <p class="stat-number"><?php echo $volunteerDrivers; ?></p>
            </div>

            <div class="stat-box">
                <h3>Passengers</h3>
                <p class="stat-number"><?php echo $passengers; ?></p>
            </div>

            <div class="stat-box">
                <h3>Ongoing Reports</h3>
                <p class="stat-number"><?php echo $ongoingReports; ?></p>
            </div>
         </div>


         <div class="navigation-bar">

            <a href="MonitorRides.php" class="navigation-btn">
                Monitor Ride Status
            </a>

            <a href="ManageUser.php" class="navigation-btn">
                Manage Users
            </a>

            <a href="ManagePenalty.php" class="navigation-btn">
                Manage Penalties
            </a>

            <a href="Report.php" class="navigation-btn">
                Reports & Carbon Emissions
            </a>

            <a href="AdminLog.php" class="navigation-btn">
                Admin Activity Log
            </a>
        </div>

        <a href="logout.php" class="logout-btn">
            Logout
        </a>
    </div>


</body>
</html>