<?php
    include 'dbconn.php';
    session_start();

    $loggedInUserID = $_SESSION['user_id'] ?? 1;

    $passengerQuery = "SELECT PassengerID FROM passengerprofile WHERE UserID = '$loggedInUserID' LIMIT 1";
    $rawPassengerData = mysqli_query($conn, $passengerQuery);
    $passengerData = ($rawPassengerData && mysqli_num_rows($rawPassengerData) > 0) ? mysqli_fetch_assoc($rawPassengerData) : null;
    $passengerID = $passengerData['PassengerID'] ?? 0;

    $query = "SELECT req.RideRequestID, p.PreferredPickUpLocation AS PickUpLocation, p.PreferredDropOffLocation AS DropOffLocation, 
                     drv.Username AS DriverName,
                     v.StarRating,
                     v.DistanceBetweenKM,
                     CONCAT(c.VehicleModel, ' - ', c.CarPlateNumber, ' (', c.VehicleColour, ', Capacity: ', c.VehicleCapacity, ')') AS VehicleDetails
              FROM riderequest req
              LEFT JOIN passengerprofile p ON req.PassengerID = p.PassengerID
              LEFT JOIN volunteerdriverprofile v ON req.VolunteerID = v.VolunteerID
              LEFT JOIN user drv ON v.UserID = drv.UserID
              LEFT JOIN car c ON req.VolunteerID = c.VolunteerID
              WHERE req.PassengerID = '$passengerID' AND req.RideRequestStatus = 'Ongoing'
              ORDER BY req.RideRequestID DESC LIMIT 1";
              
    $result = mysqli_query($conn, $query);
    $rideData = ($result && mysqli_num_rows($result) > 0) ? mysqli_fetch_assoc($result) : null;

    $rideDistance = (isset($rideData['DistanceBetweenKM']) && $rideData['DistanceBetweenKM'] !== null) 
                    ? $rideData['DistanceBetweenKM'] . ' km Away' 
                    : 'Distance not available';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ride Request Status - Ongoing</title>
    <link rel="stylesheet" href="styleP.css">
    <style>
        body {
            background-color: #e8f1f5;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .status-box { 
            width: 780px; 
            max-width: 95% !important; 
            margin: 30px auto 20px auto; 
            padding: 40px 30px; 
            border: 1px solid #000;
            border-radius: 12px;
            display: flex; 
            align-items: center; 
            gap: 30px; 
            box-sizing: border-box; 
            background-color: #eef8ff;
        }
        .placeholder-img { 
            width: 200px; 
            height: 200px; 
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
            background-color: #525252;
            color: white;
            border-radius: 8px;
            border: 1px solid #333;
            cursor: pointer;
        }
        .baseBtn:hover {
            background-color: #494949;
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
                width: 140px !important;
                height: 140px !important;
                margin: 0 auto !important;
            }
        }
    </style>
</head>
<body>

    <div class="center-wrapper" style="text-align: center; padding-top: 40px;">
        <h2>Ride Request Status</h2>
        
        <div style="margin: 15px 0;">
            <label>Status: </label>
            <span style="display: inline-block; padding: 5px 40px; border: 1px solid #666; background: #fff; border-radius: 4px;">Ongoing</span>
        </div>

        <div class="status-box">
            <img src="profilepic1.jpg" alt="Driver Photo" class="placeholder-img">
            <?php if ($ride && !empty($ride['DriverName'])): ?>
                <div style="text-align: left; line-height: 2.3; font-size: 16px;">
                    <strong>Driver:</strong> <span><?php echo htmlspecialchars($ride['DriverName']); ?> (<?php echo htmlspecialchars($ride['StarRating'] ?? '5.0'); ?> ★)</span><br>
                    <strong>Vehicle Details:</strong> <span><?php echo htmlspecialchars($ride['VehicleDetails'] ?? 'N/A'); ?></span><br>
                    <strong>Preferred Destination:</strong> <span><?php echo htmlspecialchars($ride['DropOffLocation']); ?></span><br>
                    <strong>Preferred Pick-Up Point:</strong> <span><?php echo htmlspecialchars($ride['PickUpLocation']); ?></span><br>
                    <strong>Distance:</strong> <span><?php echo htmlspecialchars($rideDistance); ?></span>
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

        <div style="margin-bottom: 15px;">
            <a href="RideAcceptedP.php" style="color: #0066cc; font-size: 14px; text-decoration: underline;">If driver accepts</a>
        </div>

        <div>
            <?php if ($ride && isset($ride['RideRequestID'])): ?>
                <button type="button" class="baseBtn" onclick="window.location.href='RideCancelledP.php?ride_id=<?php echo $ride['RideRequestID']; ?>';">Cancel Request</button>
            <?php else: ?>
                <button type="button" class="baseBtn" style="background-color: #ccc; cursor: not-allowed;" disabled>Cancel Request</button>
            <?php endif; ?>
        </div>
    </div>

</body>
</html>