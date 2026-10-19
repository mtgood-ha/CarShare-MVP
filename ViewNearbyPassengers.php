<?php
    session_start();
    include 'dbconn.php';

    $userID = $_SESSION["user_id"];
    $passenger = "";

    if ($userID) {
        $query = "SELECT u.userID AS PassengerID, u.Name, pp.PreferredPickUpLocation AS PickUpLocation, pp.PreferredDropOffLocation AS DropOffLocation 
                  FROM user u
                  JOIN passengerprofile pp ON u.userID = pp.passengerID
                  WHERE u.RoleID = 2
                  ORDER BY RAND() LIMIT 1"; 

        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) > 0) {
            $passenger = mysqli_fetch_assoc($result);
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nearby Passengers</title>
    <link rel="stylesheet" href="styleV.css">
</head>
<body>
    <div class="center-wrapper" style="max-width: 800px;">
        <h2>Nearby APU Student Passengers</h2>

        <div style="width: 100%;">
            
            <?php if ($passenger): ?>
                <div class="passengerInfo textBorder" style="display: flex; flex-direction: row; align-items: center; padding: 25px; gap: 35px; margin-bottom: 20px;">
                    
                    <div class="ProfilePic" style="flex-shrink: 0;">
                        <img src="profilepic2.jpg" width="150" height="150" style="border: 1px solid #434242; border-radius: 5px; object-fit: cover;">
                    </div>
                    
                    <div class="info-section" style="text-align: left; display: flex; flex-direction: column; width: 100%;">
                        <p class="passengerName" style="margin: 5px 0;">Passenger: <strong><?php echo htmlspecialchars($passenger['Name']); ?></strong></p>
                        <p class="DropOffLocation" style="margin: 5px 0;">Preferred Destination: <strong><?php echo htmlspecialchars($passenger['DropOffLocation']); ?></strong></p>
                        <p class="PickUpfLocation" style="margin: 5px 0;">Preferred Pick Up Location: <strong><?php echo htmlspecialchars($passenger['PickUpLocation']); ?></strong></p>

                        <h3 style="margin: 15px 0 10px 0;">2 km away</h3>
                        <a href="RideRequestStatusPage.php?passenger_id=<?php echo urlencode($passenger['PassengerID']); ?>" class="baseBtn" style="width: 100px; padding: 10px 0; margin-top: 5px;">Select</a>
                    </div>
                    
                </div>
            <?php else: ?>
                <p style="text-align: center;">No nearby passengers found.</p>
            <?php endif; ?>

        </div>
    </div>

    <div class="buttonDisplay">
        <button style="margin-top: 30px" type="button" class="baseBtn" onclick="window.location.href='MainPageVD.php';">Back</button>
    </div>
</body>
</html>