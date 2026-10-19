<?php
    session_start(); 
    include 'dbconn.php'; 

    $userID = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
    $passenger = null;
    $rating = "No rating";

    if ($userID) {
        $query = "SELECT u.userID AS PassengerID, u.Name, pp.PreferredPickUpLocation AS PickUpLocation, pp.PreferredDropOffLocation AS DropOffLocation, pp.StarRating
                  FROM user u
                  JOIN passengerprofile pp ON u.userID = pp.passengerID
                  WHERE u.RoleID = 2
                  ORDER BY RAND() LIMIT 1"; 
                  
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) > 0) {
            $passenger = mysqli_fetch_assoc($result);
            
            if (!empty($passenger['StarRating'])) {
                $rating = $passenger['StarRating'] . " ★";
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Incoming Ride Offer</title>
    <link rel="stylesheet" href="styleV.css?v=2">
</head>
<body>

<div class="center-wrapper" style="max-width: 750px;">
    <h2 style="text-align: center;">Incoming Ride Offer!</h2>

    <div class="textBorder centerText buttonColumn" style="max-width: 700px; height: 600px; background-color: rgba(255, 255, 255, 0.897); border-radius: 20px;">
    <?php if ($passenger): ?>
        <div class="ProfilePic" style="text-align: center; margin-bottom: 20px;">
            <img src="profilepic3.jpg" width="150" height="150" style="background-color: #d3d3d3; object-fit: cover; border: 1px solid #434242"> <br>
        </div>

        <div style="text-align: center; font-size: 1.1rem; color: #444; display: flex; flex-direction: column; gap: 10px;">
            <p style="margin: 0;">Passenger: <strong><?php echo htmlspecialchars($passenger['Name']); ?></strong></p>
            <p style="margin: 0;">Preferred Pick-Up Point:<br> <strong><?php echo htmlspecialchars($passenger['PickUpLocation']); ?></strong></p>
            <p style="margin: 0;">Preferred Destination:<br> <strong><?php echo htmlspecialchars($passenger['DropOffLocation']); ?></strong></p>
            
            <div class="ShowRating" style="margin-top: 10px;">
                Rating: <strong><?php echo htmlspecialchars($rating); ?></strong>
            </div>
        </div>

        <div class="button-group" style="margin-top: 25px; display: flex; justify-content: center; gap: 15px;">
            <button type="button" class="baseBtn" onclick="window.location.href='MainPageVD.php';">Decline</button>
            <button type="button" class="baseBtn" onclick="window.location.href='acceptedridefrompassenger.php?passenger_id=<?php echo urlencode($passenger['PassengerID']); ?>&pic=3';">Accept</button>
        </div>

    <?php else: ?>
        <p style="text-align: center; margin-top: 50px;">No incoming ride offers at the moment.</p>
        <div class="buttonDisplay" style="text-align: center; margin-top: 30px;">
            <button type="button" class="baseBtn" onclick="window.location.href='MainPageVD.php';">Back</button>
        </div>
    <?php endif; ?> 
    </div>
</div>

</body>
</html>