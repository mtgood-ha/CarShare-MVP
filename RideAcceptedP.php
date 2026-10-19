<?php
    include 'dbconn.php';
    session_start();

    $loggedInUserID = $_SESSION['user_id'] ?? 1;

    $passengerQuery = mysqli_query($conn, "SELECT PassengerID FROM passengerprofile WHERE UserID = '$loggedInUserID' LIMIT 1");
    $passengerData = ($passengerQuery && mysqli_num_rows($passengerQuery) > 0) ? mysqli_fetch_assoc($passengerQuery) : null;
    $passengerID = $passengerData['PassengerID'] ?? 1;

    $query = "SELECT req.RideRequestID AS RideID, req.PickUpLocation, req.DropOffLocation, 
                     drv.Username AS DriverName,
                     v.StarRating,
                     v.DistanceBetweenKM,
                     CONCAT(c.VehicleModel, ' - ', c.CarPlateNumber, ' (', c.VehicleColour, ', Capacity: ', c.VehicleCapacity, ')') AS VehicleDetails
              FROM riderequest req
              LEFT JOIN volunteerdriverprofile v ON req.VolunteerID = v.VolunteerID
              LEFT JOIN user drv ON v.UserID = drv.userID
              LEFT JOIN car c ON req.VolunteerID = c.VolunteerID
              WHERE req.PassengerID = '$passengerID' AND req.RideRequestStatus = 'Ongoing'
              ORDER BY req.RideRequestID DESC LIMIT 1";

    $result = mysqli_query($conn, $query);
    $rideData = ($result && mysqli_num_rows($result) > 0) ? mysqli_fetch_assoc($result) : null;
    $rideDistance = (isset($rideData['DistanceBetweenKM']) && $rideData['DistanceBetweenKM'] !== null) 
                    ? $rideData['DistanceBetweenKM'] . ' km Away' 
                    : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ride Request Status - Accepted</title>
    <link rel="stylesheet" href="styleP.css">
    <style>
        .status-box { 
            width: 780px; 
            max-width: 95%; 
            margin: 30px auto 15px auto; 
            padding: 45px 40px; 
            border: 2px solid #000; 
            border-radius: 12px; 
            display: flex; 
            align-items: center; 
            gap: 40px; 
            box-sizing: border-box; 
            background-color: #ffffff;
        }
        .placeholder-img { 
            width: 150px; 
            height: 150px; 
            background: #ddd; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            object-fit: cover; 
            flex-shrink: 0; 
            border-radius: 4px;
        }
        .baseBtn {
            white-space: nowrap;
            width: auto;
            display: inline-block;
            padding: 12px 30px;
        }
        .btn-disabled-gray {
            background-color: #6c757d !important;
            color: #ffffff !important;
            border: 1px solid #5a6268 !important;
            cursor: not-allowed !important;
            opacity: 1;
        }
        .btn-enabled {
            background-color: #fff;
            color: #333;
            border: 1px solid #666;
            cursor: pointer;
        }

        @media screen and (max-width: 768px) {
            .status-box {
                flex-direction: column !important;
                text-align: center !important;
                padding: 20px 15px !important;
                gap: 20px !important;
            }
            .status-box > div {
                text-align: left !important;
                width: 100% !important;
            }
            .placeholder-img {
                width: 130px !important;
                height: 130px !important;
                margin: 0 auto !important;
            }
            .center-wrapper div[style*="display: flex"] {
                flex-direction: column !important;
                gap: 10px !important;
            }
            .baseBtn {
                width: 100% !important;
            }
        }
    </style>
</head>
<body>

    <div class="center-wrapper" style="text-align: center; padding-top: 20px;">
        <h2>Ride Request Status</h2>
        
        <div style="margin: 15px 0;">
            <label>Status: </label>
            <span style="display: inline-block; padding: 5px 40px; border: 1px solid #666; background: #fff; border-radius: 4px;">Accepted!</span>
        </div>

        <div class="status-box">
            <img src="profilepic1.jpg" alt="Driver Photo" class="placeholder-img">
            <?php if ($ride && !empty($ride['DriverName'])): ?>
                <div style="text-align: left; line-height: 2.3; font-size: 16px;">
                    <strong>Driver:</strong> <span><?php echo htmlspecialchars($ride['DriverName']); ?> (<?php echo htmlspecialchars($ride['StarRating'] ?? '5.0'); ?> ★)</span><br>
                    <strong>Vehicle Details:</strong> <span><?php echo htmlspecialchars($ride['VehicleDetails'] ?? 'N/A'); ?></span><br>
                    <strong>Preferred Destination:</strong> <span><?php echo htmlspecialchars($ride['DropOffLocation']); ?></span><br>
                    <strong>Preferred Pick-Up Point:</strong> <span><?php echo htmlspecialchars($ride['PickUpLocation']); ?></span><br>
                    <strong>Distance:</strong> <span><?php echo htmlspecialchars($rideDistance); ?> (ETA: <span id="countdown">10s</span>)</span>
                </div>
            <?php else: ?>
                <div style="text-align: left; line-height: 2.3; font-size: 16px;">
                    <strong>Driver:</strong> <span>Searching for driver...</span><br>
                    <strong>Vehicle Details:</strong> <span>-</span><br>
                    <strong>Preferred Destination:</strong> <span>-</span><br>
                    <strong>Preferred Pick-Up Point:</strong> <span>-</span><br>
                    <strong>Distance:</strong> <span>-</span>
                </div>
            <?php endif; ?>
        </div>

        <div style="margin-bottom: 15px; display: flex; justify-content: center; gap: 15px;">
            <button type="button" class="baseBtn" style="background-color: #fff; color: #333; border: 1px solid #666; cursor: default;" disabled>Ride in progress...</button>
            <button type="button" id="completedBtn" class="baseBtn btn-disabled-gray" onclick="window.location.href='RideCompletedP.php?request_id=<?php echo $ride['RideID'] ?? ''; ?>';" disabled>Completed Ride</button>
        </div>
    </div>

    <script>
        let timeLeft = 10;
        const countdownElement = document.getElementById('countdown');
        const completedBtn = document.getElementById('completedBtn');

        const countdownTimer = setInterval(function() {
            timeLeft--;
            countdownElement.textContent = timeLeft;
            if (timeLeft <= 0) {
                clearInterval(countdownTimer);
                countdownElement.textContent = "Arrived";
                
                completedBtn.removeAttribute('disabled');
                completedBtn.classList.remove('btn-disabled-gray');
                completedBtn.classList.add('btn-enabled');
            }
        }, 1000);
    </script>

</body>
</html>