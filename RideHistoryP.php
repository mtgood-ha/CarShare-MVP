<?php
    include 'dbconn.php';
    session_start();
    date_default_timezone_set('Asia/Kuala_Lumpur');

    $loggedInUserID = $_SESSION['user_id'] ?? 0;

    $rawPassengerData = mysqli_query($conn, "SELECT PassengerID FROM passengerprofile WHERE UserID = '$loggedInUserID' LIMIT 1");
    $passengerData = mysqli_fetch_assoc($rawPassengerData);
    $passengerID = $passengerData['PassengerID'] ?? 0;

    $query = "SELECT r.RideID, r.DateOfRides, r.StartTime, r.EndTime, r.CarPlateNumber, 
                     req.PickUpLocation, req.DropOffLocation, u.Username AS DriverName,
                     p.DistanceBetweenKM, f.StarRating, f.Comment
              FROM rides r
              INNER JOIN riderequest req ON r.RideRequestID = req.RideRequestID
              LEFT JOIN passengerprofile p ON req.PassengerID = p.PassengerID
              LEFT JOIN volunteerdriverprofile d ON req.VolunteerID = d.VolunteerID
              LEFT JOIN user u ON d.UserID = u.UserID
              LEFT JOIN feedback f ON r.RideRequestID = f.RideID AND f.FeedbackDirection = 'Passenger to Driver'
              WHERE req.PassengerID = '$passengerID' 
              ORDER BY r.DateOfRides DESC, r.StartTime DESC, r.EndTime DESC";

    $result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ride history</title>
    <link rel="stylesheet" href="styleP.css">
    <style>
        .center-wrapper {
            max-width: 950px;
            margin: 0 auto;
            padding: 20px;
            text-align: center;
        }
        .history-scroll-box {
            width: 100%;
            max-height: 600px;
            margin: 20px auto;
            border: 1px solid #0e0e0e;
            border-radius: 8px;
            overflow-y: auto;
            padding: 20px;
            background: #f3fbff;
            text-align: left;
            box-sizing: border-box;
        }
        .history-card {
            border: 1px solid #070707;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 15px;
            display: flex;
            justify-content: space-between;
            background: #f3fbff;
            box-sizing: border-box;
        }
        .history-left {
            width: 58%;
        }
        .history-right {
            width: 38%;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
        }
        .comment-box-display {
            border: 1px solid #101010;
            padding: 10px;
            height: 75px;
            margin-top: 5px;
            background: #fafafa;
            font-size: 14px;
            overflow-y: auto;
        }

        @media screen and (max-width: 768px) {
            .history-card {
                flex-direction: column !important;
                gap: 15px !important;
            }
            .history-left, .history-right {
                width: 100% !important;
            }
        }
    </style>
</head>
<body>

    <div class="center-wrapper">
        <h2>Ride history</h2>

        <div class="history-scroll-box" id="historyScrollContainer">
            <?php if ($result && mysqli_num_rows($result) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <div class="history-card">
                        <div class="history-left">
                            <p>Date: <span><?php echo htmlspecialchars($row['DateOfRides']); ?></span></p>
                            <p>Start time: <span><?php echo htmlspecialchars($row['StartTime']); ?></span></p>
                            <p>End time: <span><?php echo htmlspecialchars($row['EndTime']); ?></span></p>
                            <br>
                            <p>Driver: <span><?php echo htmlspecialchars($row['DriverName'] ?? 'Unknown'); ?></span></p>
                            <p>Vehicle details: <span><?php echo htmlspecialchars($row['CarPlateNumber']); ?></span></p>
                            <p>Route: <span><?php echo htmlspecialchars($row['PickUpLocation'] ?? 'Origin'); ?> --> <?php echo htmlspecialchars($row['DropOffLocation'] ?? 'Destination'); ?></span></p>
                            <p>Distance: <span><?php echo isset($row['DistanceBetweenKM']) ? htmlspecialchars($row['DistanceBetweenKM']) . ' km' : 'N/A'; ?></span></p>
                            <p>Ratings: <span><?php echo isset($row['StarRating']) ? htmlspecialchars($row['StarRating']) : 'X.X'; ?></span></p>
                        </div>
                        <div class="history-right">
                            <div>
                                <p>Comment:</p>
                                <div class="comment-box-display"><?php echo !empty($row['Comment']) ? htmlspecialchars($row['Comment']) : 'No comment'; ?></div>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p style="text-align: center; color: #666; font-style: italic;">No ride history found.</p>
            <?php endif; ?>
        </div>

        <button onclick="window.location.href='MainPageP.php';" class="baseBtn" style="margin-top: 10px;">
            Back
        </button>
    </div>

</body>
</html>