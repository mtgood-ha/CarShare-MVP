<?php
    session_start();
    include 'dbconn.php';

    $userID = $_SESSION["user_id"]; 
    $RidesDone = "0"; 

    $query = "SELECT NumberOfRidesDone FROM volunteerdriverprofile WHERE UserID = '$userID'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $RidesDone = $row["NumberOfRidesDone"];
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Personal Achievements</title>
    <link rel="stylesheet" href="styleV.css">
</head>
<body>
    <div class="center-wrapper">
        <h2>Personal Achievements</h2>

        <div class="achievement-box textBorder">
            <p>Amount of rides done:</p>
            <div class="form-row">
                <input type="text" value="<?php echo htmlspecialchars($RidesDone); ?>" readonly>
            </div>
        </div>
        
        <button type="button" class="baseBtn" onclick="window.location.href='ManageAccount.php';">Back</button>
    </div>
</body>
</html>