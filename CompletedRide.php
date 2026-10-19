<?php
    session_start();
    include 'dbconn.php';

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['rating'])) {
        
        $ride_id = isset($_POST['RideID']) ? $_POST['RideID'] : null;
        $rating = isset($_POST['rating']) ? $_POST['rating'] : null;
        $comments = isset($_POST['comments']) ? $_POST['comments'] : '';
        
        $formatted_rating = number_format((float)$rating, 1, '.', '');
        $feedbackDirection = 'Driver to Passenger';

        if ($ride_id && $rating) {
            $query = "INSERT INTO feedback (RideID, StarRating, Comment, FeedbackDirection) 
                      VALUES ('$ride_id', '$formatted_rating', '$comments', '$feedbackDirection')";
                      
            if (mysqli_query($conn, $query)) {
                header("Location: feedbacksubmitted.html");
                exit();
            } else {
                echo "Feedback saving error: " . mysqli_error($conn);
            }
        }
    }

    $incoming_ride_id = isset($_GET['request_id']) ? $_GET['request_id'] : '';
    $rideData = ['RideID' => $incoming_ride_id];

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
        
        <div class="form-row" style="padding-right: 10px">
            <label for="txtStatus" style="font-weight: bold">Status:</label>
            <input type="text" id="txtStatus" value="Completed!" style="text-align: center; font-weight: bold;" readonly>
        </div>

        <form action="" method="POST" style="width: 100%;" onsubmit="if(document.getElementById('driverRating').value == '0') { alert('You must rate the driver'); return false; }">
            <input type="hidden" name="RideID" value="<?php echo htmlspecialchars($rideData['RideID']); ?>">
            
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
                    <textarea name="comments" id="comments" required style="width: 100%; height: 150px; padding: 10px; border: 1px solid #242323; box-sizing: border-box; resize: vertical; font-family: inherit; font-size: 15px;"></textarea>
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