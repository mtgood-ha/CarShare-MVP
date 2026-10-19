<?php
    session_start();
    include 'dbconn.php';  
    date_default_timezone_set('Asia/Kuala_Lumpur');

    $pickup = "";
    $dropoff = "";

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cancel_request'])) {
        $req_id = $_POST['request_id'];
        $query = "UPDATE riderequest SET RideRequestStatus = 'Cancelled' WHERE RideRequestID = '$req_id'";
        
        if(mysqli_query($conn, $query)){
            header("Location: CancelledRide.html"); exit();
        }else{
            echo "Database cannot be updated. " . mysqli_error($conn);
        }
    }

    if (isset($_GET['passenger_id'])) {
        $passenger_id = $_GET['passenger_id'];
        $session_user_id = $_SESSION['user_id'];
        $query = "SELECT VolunteerID FROM volunteerdriverprofile WHERE userID = '$session_user_id'";

        $result = mysqli_query($conn, $query);
        
        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $volunteer_id = $row['VolunteerID']; 
        } else {
            die("Volunteer ID cannot be found.");
        }

        $query = "SELECT PreferredPickUpLocation, PreferredDropOffLocation FROM passengerprofile WHERE passengerID = '$passenger_id'";

        $result = mysqli_query($conn, $query);
        
        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $pickup = $row['PreferredPickUpLocation'];
            $dropoff = $row['PreferredDropOffLocation'];
        }

        $status = "Ongoing";
        $requestTime = date('Y-m-d H:i:s');
        $query = "INSERT INTO riderequest (PassengerID, VolunteerID, RideRequestStatus, RequestTime, PickUpLocation, DropOffLocation)
                  VALUES ('$passenger_id', '$volunteer_id', '$status', '$requestTime', '$pickup', '$dropoff')";
        if (mysqli_query($conn, $query)) {
            $new_request_id = mysqli_insert_id($conn);
            mysqli_close($conn); 
            header("Location: RideRequestStatusPage.php?request_id=" . $new_request_id);
            exit();
        } else {
            echo "Ride cannot be updated into ride request database" . mysqli_error($conn);
        }
    }

    $request_id = $_GET["request_id"];

    if ($request_id) {
        $query = "SELECT u.Name, pp.StarRating, rr.PickUpLocation, rr.DropOffLocation, rr.RideRequestStatus FROM riderequest rr
                  JOIN user u ON rr.PassengerID = u.UserID
                  LEFT JOIN passengerprofile pp ON u.UserID = pp.PassengerID
                  WHERE rr.RideRequestID = '$request_id'";

        $result = mysqli_query($conn, $query);
        
        if (mysqli_num_rows($result) > 0) {

            $row = mysqli_fetch_assoc($result);
            
            $stars = $row['StarRating'] ? $row['StarRating'] . " ★" : "No rating available.";
            $passengerRating = $row['Name'] . " (" . $stars . ")";
            $currentStatus = $row['RideRequestStatus'];
            $prefDestination = $row['DropOffLocation'];
            $prefPickUp = $row['PickUpLocation'];
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ride Request Status</title>
    <link rel="stylesheet" href="styleV.css">
</head>
<body>

    <div class="center-wrapper" style="max-width: 750px;">
        <h2 style="text-align: center; width: 100%;">Ride Request Status</h2>

        <div class="form-row" style="padding-right: 10px">
            <label for="RideRequestStatus" style="font-weight: bold">Status:</label>
            <input type="text" id="RideRequestStatus" value="<?php echo htmlspecialchars($currentStatus ?? 'Pending'); ?>" style="text-align: center; font-weight: bold;" readonly>
        </div>

        <div class="textBorder centerText" style="padding: 40px; box-sizing: border-box; justify-content: space-around;">
            
            <div style="flex: 1; display: flex; justify-content: center;">
                <img src="profilepic2.jpg" alt="Passenger Image" style="width: 200px; height: 200px; background-color: #d3d3d3; object-fit: cover;">
            </div>
            
            <div style="flex: 1; text-align: left; display: flex; flex-direction: column; gap: 8px; color: #444; font-size: 1.1rem;">
                <div><strong>Passenger:</strong> <?php echo htmlspecialchars($passengerRating ?? '(Star-rating)'); ?></div>
                <div><strong>Preferred Destination:</strong><br> <?php echo htmlspecialchars($prefDestination ?? ''); ?></div>
                <div><strong>Preferred Pick-Up Point:</strong><br> <?php echo htmlspecialchars($prefPickUp ?? ''); ?></div>
                <div><strong>Distance:</strong> 2 km Away</div>
            </div>

        </div>

        <div class="buttonDisplay bottomButton" style="margin-top: 15px;">
            <a href="AcceptedRideFromPassenger.php?request_id=<?php echo urlencode($request_id); ?>&pic=2">If passenger accepts</a>
        
            <form action="" method="POST" style="margin: 0;">
                <input type="hidden" name="request_id" value="<?php echo htmlspecialchars($request_id ?? ''); ?>">
                <input type="submit" name="cancel_request" value="Cancel Request" class="baseBtn" style="width: auto; padding: 15px 30px;">
            </form>
        </div>
    </div>

</body>
</html>