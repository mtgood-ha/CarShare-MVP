<?php
    include 'dbconn.php';
    session_start();

    $loggedInUserID = $_SESSION['user_id'] ?? 1;

    $passengerQuery = "SELECT PreferredPickUpLocation, PreferredDropOffLocation FROM passengerprofile WHERE UserID = '$loggedInUserID' LIMIT 1";
    $rawPassengerData = mysqli_query($conn, $passengerQuery);
    $passengerData = ($rawPassengerData && mysqli_num_rows($rawPassengerData) > 0) ? mysqli_fetch_assoc($rawPassengerData) : null;

    $pickupLocation = $passengerData['PreferredPickUpLocation'] ?? '';
    $dropoffLocation = $passengerData['PreferredDropOffLocation'] ?? '';
    
    $query = "SELECT p.VolunteerID, u.Username, p.StarRating, p.DistanceBetweenKM, c.VehicleModel, c.CarPlateNumber 
              FROM user u
              JOIN volunteerdriverprofile p ON u.userID = p.userID 
              LEFT JOIN car c ON p.VolunteerID = c.VolunteerID
              WHERE p.DriverStatus = 'Available'";
              
    $result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request Ride</title>
    <link rel="stylesheet" href="styleP.css">
</head>
<body>

    <div class="center-wrapper">
        <h2>Request ride</h2>

        <form action="processRideRequestP.php" method="POST" id="rideRequestForm" style="width: 100%; display: flex; flex-direction: column; align-items: center;">
            
            <div class="textBorder" style="width: 100%; max-width: 650px;">
                <div class="form-row">
                    <label for="txtPickup">Pick-up location:</label>
                    <input type="text" id="txtPickup" name="txtPickup" value="<?php echo htmlspecialchars($pickupLocation); ?>" readonly style="background-color: #e9ecef; cursor: not-allowed;">
                </div>

                <div class="form-row">
                    <label for="txtDropoff">Drop-off location:</label>
                    <input type="text" id="txtDropoff" name="txtDropoff" value="<?php echo htmlspecialchars($dropoffLocation); ?>" readonly style="background-color: #e9ecef; cursor: not-allowed;">
                </div>

                <div style="width: 100%; margin-top: 10px;">
                    <p style="font-size: 17px; font-weight: 500; color: #222222; margin-bottom: 15px;">Nearby volunteer drivers:</p>

                    <div style="display: flex; flex-direction: column; gap: 15px; width: 100%;">
                        <?php if ($result && mysqli_num_rows($result) > 0): ?>
                            <?php while ($driver = mysqli_fetch_assoc($result)): ?>
                                <?php 
                                    $driverDistance = isset($driver['DistanceBetweenKM']) ? $driver['DistanceBetweenKM'] . ' km Away' : 'Distance not available';
                                ?>
                                <label style="display: flex; align-items: center; background-color: #ffffff; border: 1px solid #555555; border-radius: 8px; padding: 15px; cursor: pointer; gap: 15px;">
                                    <input type="radio" name="driver_id" value="<?php echo htmlspecialchars($driver['VolunteerID']); ?>" required>
                                    <img src="profilepic1.jpg" alt="Driver Photo" style="width: 50px; height: 50px; border-radius: 8px; object-fit: cover; border: 1px solid #333333;">
                                    <div style="font-size: 15px; color: #222222; line-height: 1.4;">
                                        <strong>Driver:</strong> <?php echo htmlspecialchars($driver['Username']); ?> (<?php echo htmlspecialchars($driver['StarRating']); ?> ★)<br>
                                        <strong>Vehicle Details:</strong> <?php echo htmlspecialchars(($driver['VehicleModel'] ?? 'N/A') . ' (' . ($driver['CarPlateNumber'] ?? 'N/A') . ')'); ?><br>
                                        <span><?php echo htmlspecialchars($driverDistance); ?></span>
                                    </div>
                                </label>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <p style="color: #666; font-style: italic;">No available volunteer drivers at the moment.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="buttonDisplay">
                <button type="button" class="baseBtn" onclick="window.location.href='MainPageP.php';">Back</button>
                <button type="submit" class="baseBtn">Submit ride request</button>
            </div>

        </form>
    </div>

</body>
</html>