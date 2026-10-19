<?php
include 'dbconn.php';
session_start();

$loggedInUserID = $_SESSION['user_id'] ?? 1;

$passengerQuery = mysqli_query($conn, "SELECT PassengerID FROM passengerprofile WHERE UserID = '$loggedInUserID' LIMIT 1");
$passengerData = mysqli_fetch_assoc($passengerQuery);
$passengerID = $passengerData['PassengerID'] ?? 0;
$rideQuery = mysqli_query($conn, "SELECT RideRequestID FROM riderequest WHERE PassengerID = '$passengerID' AND RideRequestStatus = 'Ongoing' LIMIT 1");
$ongoingRide = ($rideQuery && mysqli_num_rows($rideQuery) > 0) ? mysqli_fetch_assoc($rideQuery) : null;
$rideRequestID = $ongoingRide['RideRequestID'] ?? 0;

$errorMsg = ""; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $rating = intval($_POST['rating'] ?? 0);
    $commentsEntered = trim($_POST['comments'] ?? '');

    if ($rating < 1 || $rating > 5) {
        $errorMsg = "Please provide a rating before submitting.";
    } else {
        if ($rideRequestID > 0) {
            if ($commentsEntered === "") {
                $commentToSave = "N/A";
            } else {
                $commentToSave = $rawComments;
            }

            $safeComment = mysqli_real_escape_string($conn, $commentToSave);

            $insertFeedback = "INSERT INTO feedback (RideID, FeedbackDirection, StarRating, Comment) 
                               VALUES ('$rideRequestID', 'Passenger to Driver', '$rating', '$safeComment')";
            mysqli_query($conn, $insertFeedback);
            
            mysqli_query($conn, "UPDATE riderequest SET RideRequestStatus = 'Completed' WHERE RideRequestID = '$rideRequestID'");
            mysqli_query($conn, "UPDATE rides SET RideStatus = 'Completed' WHERE RideRequestID = '$rideRequestID'");
        }

        header("Location: FeedbackSuccessP.html");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Completed Ride</title>
    <link rel="stylesheet" href="styleV.css">
    <link rel="stylesheet" href="star.css">
</head>
<body>
    
    <div class="center-wrapper" style="max-width: 750px;">
        <h2 style="text-align: center; width: 100%;">Ride Request Status</h2>
        
        <?php if (!empty($errorMsg)): ?>
            <p style="color: red; text-align: center; font-weight: bold; width: 100%; margin-bottom: 15px;"><?php echo $errorMsg; ?></p>
        <?php endif; ?>

        <div class="form-row" style="padding-right: 10px">
            <label for="txtStatus" style="font-weight: bold">Status:</label>
            <input type="text" id="txtStatus" value="Completed!" style="text-align: center; font-weight: bold;" readonly>
        </div>

        <form action="" method="POST" style="width: 100%;">
            <input type="hidden" name="RideRequestID" value="<?php echo htmlspecialchars($rideRequestID); ?>">
            
            <div class="textBorder" style="padding: 40px; box-sizing: border-box; display: flex; flex-direction: column; gap: 25px; text-align: left;">
                
                <div style="font-size: 1.1rem; color: #434242;">How was your ride?</div>
                
                <div style="display: flex; align-items: center; gap: 10px;">
                    <label style="font-size: 1.1rem; color: #434242;">Rating:</label>
                    
                    <div class="star-rating" data-input-id="driverRating">
                        <span class="star" data-value="1">★</span>
                        <span class="star" data-value="2">★</span>
                        <span class="star" data-value="3">★</span>
                        <span class="star" data-value="4">★</span>
                        <span class="star" data-value="5">★</span>
                    </div>
                
                    <input type="hidden" name="rating" id="driverRating" value="0">
                </div>

                <div style="display: flex; flex-direction: column; gap: 10px;">
                    <label for="comments" style="font-size: 1.1rem; color: #434242;">Comments:</label>
                    <textarea name="comments" id="comments" style="width: 100%; height: 150px; padding: 10px; border: 1px solid #242323; box-sizing: border-box; resize: vertical; font-family: inherit; font-size: 15px;"></textarea>
                </div>

            </div>
            
            <div class="buttonDisplay sideButton" style="margin-top: 30px; gap: 40px;">
                <input type="submit" value="Submit Feedback" class="baseBtn" style="width: auto; padding: 12px 30px;">
            </div>
            
        </form>
    </div>

    <script src="starRating.js"></script>
</body>
</html>