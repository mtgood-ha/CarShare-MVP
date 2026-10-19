<?php
    include 'dbconn.php';
    session_start();

    $rideID = $_GET['ride_id'] ?? null;

    if ($rideID) {
        mysqli_query($conn, "UPDATE riderequest SET RideRequestStatus = 'Cancelled' WHERE RideRequestID = '$rideID'");
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ride Cancelled</title>
    <link rel="stylesheet" href="styleP.css">
    <style>
        .baseBtn {
            white-space: nowrap;
            width: auto;
            display: inline-block;
            padding: 12px 30px;
        }
    </style>
</head>
<body>

    <div class="center-wrapper" style="text-align: center; margin-top: 150px;">
        <h1 style="font-weight: normal; color: #333; margin-bottom: 30px;">You've cancelled the ride</h1>
        
        <button type="button" class="baseBtn" onclick="window.location.href='MainPageP.php';">
            Back to homepage
        </button>
    </div>

</body>
</html>