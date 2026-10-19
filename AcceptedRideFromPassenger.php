<?php
    session_start();
    include 'dbconn.php'; 
    date_default_timezone_set('Asia/Kuala_Lumpur');

    if (isset($_GET['passenger_id'])) {
        $passenger_id = $_GET['passenger_id'];
        $pic = isset($_GET['pic']) ? $_GET['pic'] : 2;
        
        $session_user_id = $_SESSION['user_id'];

        $query = "SELECT VolunteerID FROM volunteerdriverprofile WHERE userID = '$session_user_id'";
        
        $result = mysqli_query($conn, $query);
        
        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $volunteer_id = $row['VolunteerID'];
        } else {
            die("Error: Volunteer profile not found.");
        }
        
        $query = "SELECT PreferredPickUpLocation, PreferredDropOffLocation FROM passengerprofile WHERE passengerID = '$passenger_id'";
        $result = mysqli_query($conn, $query);
        
        $pickup = "";
        $dropoff = "";
        
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
            header("Location: AcceptedRideFromPassenger.php?request_id=" . $new_request_id . "&pic=" . $pic);
            exit();
        } else {
            echo "Data cannot be inserted into database. " . mysqli_error($conn);
        }
    }


    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['complete_ride'])) {
        $req_id = $_POST['request_id'];
        $session_user_id = $_SESSION['user_id'];
        
        $current_time = date('Y-m-d H:i:s');
        $current_date = date('Y-m-d');

        $query = "SELECT RequestTime FROM riderequest WHERE RideRequestID = '$req_id'";
        $result = mysqli_query($conn, $query); 

        $startTime = $current_time; 
        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $startTime = $row['RequestTime'];
        }

        $query = "SELECT c.CarPlateNumber 
                  FROM car c 
                  JOIN volunteerdriverprofile vdp ON c.VolunteerID = vdp.VolunteerID 
                  WHERE vdp.userID = '$session_user_id'";
                             
        $result = mysqli_query($conn, $query);
        
        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $carPlate = $row['CarPlateNumber'];
        } else {
            die("Register a car in car management first.");
        }

        $query = "INSERT INTO rides (DateOfRides, RideRequestID, RideStatus, CarPlateNumber, StartTime, EndTime) 
                  VALUES ('$current_date', '$req_id', 'Completed', '$carPlate', '$startTime', '$current_time')";

        if (!mysqli_query($conn, $query)) {
            echo "Error: " . mysqli_error($conn);
        }
        
        $query = "UPDATE riderequest SET RideRequestStatus = 'Completed' WHERE RideRequestID = '$req_id'";

        if (mysqli_query($conn, $query)) {
            header("Location: CompletedRide.php?request_id=" . $req_id);
            exit();
        } else {
            echo "Ride request cannot be updated " . mysqli_error($conn);
        }
    }

    $request_id = isset($_GET['request_id']) ? $_GET['request_id'] : null;
  
    $picNumber = isset($_GET['pic']) ? (int)$_GET['pic'] : 2;
    $profilePicSource = "profilepic" . $picNumber . ".jpg";
    
    $passengerName = "";
    $prefDestination = "";
    $prefPickUp = "";

    if ($request_id) {
        $query = "SELECT u.Name, pp.StarRating, rr.PickUpLocation, rr.DropOffLocation 
                  FROM riderequest rr
                  JOIN user u ON rr.PassengerID = u.UserID
                  LEFT JOIN passengerprofile pp ON u.UserID = pp.PassengerID
                  WHERE rr.RideRequestID = '$request_id'";    
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $stars = $row['StarRating'] ? $row['StarRating'] . " ★" : "No rating";
            $passengerName = $row['Name'] . " (" . $stars . ")";
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
    <title>Ongoing/Accepted Ride Request Status</title>
    <link rel="stylesheet" href="styleV.css">
</head>
<body>

    <div class="center-wrapper" style="max-width: 750px;">
        <h2 style="text-align: center; width: 100%;">Ride Request Status</h2>

        <div class="form-row" style="padding-right: 10px">
            <label for="txtStatus" style="font-weight: bold">Status:</label>
            <input type="text" id="txtStatus" value="Accepted!" style="text-align: center; font-weight: bold;" readonly>
        </div>

        <div class="textBorder centerText" style="padding: 40px; box-sizing: border-box; justify-content: space-around;">
            
            <div style="flex: 1; display: flex; justify-content: center;">
                <img src="<?php echo htmlspecialchars($profilePicSource); ?>" alt="Passenger Image" style="width: 200px; height: 200px; background-color: #d3d3d3; object-fit: cover;">
            </div>
            
            <div style="flex: 1; text-align: left; display: flex; flex-direction: column; gap: 8px; color: #444; font-size: 1.1rem;">
                <div><strong>Passenger:</strong> <?php echo htmlspecialchars($passengerName ?? '(Star-rating)'); ?></div>
                <div><strong>Preferred Destination:</strong><br> <?php echo htmlspecialchars($prefDestination ?? ''); ?></div>
                <div><strong>Preferred Pick-Up Point:</strong><br> <?php echo htmlspecialchars($prefPickUp ?? ''); ?></div>
                <div><strong>Distance:</strong> 2 km Away</div>
            </div>

        </div>

        <div class="buttonDisplay bottomButton">
            <button style="width: 350px; text-decoration: none;" class="baseBtn whiteBtn" id="btnRewards" disabled>Ride in progress...</button>
            
            <form action="" method="POST">
                <input type="hidden" name="request_id" value="<?php echo htmlspecialchars($request_id ?? ''); ?>">
                <input type="hidden" name="complete_ride" value="1">
                <input type="submit" value="Completed Ride" class="baseBtn" style="width: auto; padding: 15px 30px;">
            </form>
        </div>

    </div>

</body>
</html>