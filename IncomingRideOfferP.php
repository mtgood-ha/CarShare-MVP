<?php
    include 'dbconn.php';
    session_start();

    $loggedInUserID = $_SESSION['user_id'] ?? 1;

    $rawDriverData = mysqli_query($conn, "SELECT v.VolunteerID, u.Username AS DriverName, v.StarRating, v.PreferredPickUpLocation AS DriverLocation, v.DistanceBetweenKM,
    CONCAT(c.VehicleModel, ' - ', c.CarPlateNumber, ' 
    (', c.VehicleColour, ', Capacity: ', c.VehicleCapacity, ')')
    AS VehicleDetails FROM volunteerdriverprofile v JOIN user u ON v.UserID = u.UserID JOIN car c ON v.VolunteerID = c.VolunteerID ORDER BY RAND() LIMIT 1");
    $driverData = mysqli_fetch_assoc($rawDriverData);

    $volunteerID = $driverData['VolunteerID'] ?? '';
    $driverName = $driverData['DriverName'] ?? '';
    $driverRating = isset($driverData['StarRating']) ? $driverData['StarRating'] . ' ★' : '';
    $vehicleDetails = $driverData['VehicleDetails'] ?? '';
    $driverAddress = $driverData['DriverLocation'] ?? '';
    $distance = isset($driverData['DistanceBetweenKM']) ? $driverData['DistanceBetweenKM'] . ' km' : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Incoming Ride Offer</title>
    <link rel="stylesheet" href="styleP.css">
    <style>
        body {
            background-color: #e8f1f5;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .page-container {
            text-align: center;
            padding-top: 40px;
        }
        .offer-title {
            font-size: 22px;
            color: #222;
            margin-bottom: 25px;
            font-weight: bold;
        }
        .offer-box {
            width: 750px;
            max-width: 95%;
            margin: 0 auto;
            padding: 60px 50px;
            background: #ffffff;
            border: 1px solid #0f0e0e;
            border-radius: 8px;
            box-sizing: border-box;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            text-align: center;
        }
        .placeholder-img {
            width: 160px;
            height: 160px;
            margin: 0 auto 25px auto;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .placeholder-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 6px;
        }
        .offer-details {
            text-align: center;
            margin: 0 auto 30px auto;
            font-size: 15px;
            color: #333;
            line-height: 1.8;
            width: 100%;
            box-sizing: border-box;
        }
        .offer-details p {
            margin: 8px 0;
        }
        .button-group {
            display: flex;
            justify-content: center;
            gap: 20px;
        }
        .offer-btn {
            background-color: #525252;
            color: white;
            padding: 10px 35px;
            cursor: pointer;
            border-radius: 6px; 
            border: 1px solid #333333; 
            font-size: 15px;
            font-weight: 500;
        }
        .offer-btn:hover {
            background-color: #404040;
        }

        @media screen and (max-width: 768px) {
            .offer-box {
                width: 90% !important;
                padding: 25px 15px !important;
            }
            .placeholder-img {
                width: 130px !important;
                height: 130px !important;
                margin-bottom: 15px !important;
            }
            .button-group {
                flex-direction: column !important;
                gap: 12px !important;
            }
            .offer-btn {
                width: 100% !important;
                padding: 12px 0 !important;
            }
        }
    </style>
</head>
<body>

    <div class="page-container">
        <div class="offer-title">Incoming Ride Offer!</div>

        <div class="offer-box">
            <div class="placeholder-img">
                <img src="profilepic1.jpg" alt="Profile Picture">
            </div>
            
            <div class="offer-details">
                <p><b>Driver:</b> <?php echo htmlspecialchars($driverName); ?></p>
                <p><b>Rating:</b> <?php echo htmlspecialchars($driverRating); ?></p>
                <p><b>Vehicle Details:</b> <?php echo htmlspecialchars($vehicleDetails); ?></p>
                <p><b>Driver Location:</b> <?php echo htmlspecialchars($driverAddress); ?></p>
                <p><b>Distance:</b> <?php echo htmlspecialchars($distance); ?></p>
            </div>

            <div class="button-group">
                <button type="button" class="offer-btn" onclick="window.location.href='processRideRequestP.php?driver_id=<?php echo $volunteerID; ?>&distance=<?php echo urlencode($distance); ?>';">Accept</button>
                <button type="button" class="offer-btn" onclick="window.location.href='ChooseAMethod.html';">Decline</button>
            </div>
        </div>
    </div>

</body>
</html>