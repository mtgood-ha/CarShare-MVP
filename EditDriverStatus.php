<?php
    session_start();
    include 'dbconn.php';

    $userID = $_SESSION["user_id"];
    $driverStatus = ""; 
    $message = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['status'])) {
        $newStatus = mysqli_real_escape_string($conn, $_POST['status']);
        $updateQuery = "UPDATE volunteerdriverprofile SET driverStatus = '$newStatus' WHERE userID = '$userID'";
        
        if (mysqli_query($conn, $updateQuery)) {
            $message = "Status updated successfully!";
        } else {
        $message = "Updating is not successfully done. ";
        }
    }

    $query = "SELECT driverStatus FROM volunteerdriverprofile WHERE userID = '$userID'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $getDriverStatus = mysqli_fetch_assoc($result);
        $driverStatus = $getDriverStatus['driverStatus'];
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Driver Status</title>
    <link rel="stylesheet" href="styleV.css">
</head>
<body>

    <div class="center-wrapper">
        <h2>Driver's Status</h2>
        
        <div class="achievement-box textBorder">
        <form action="" method="POST" style="width: 100%;">
            
            
            <?php if ($message): ?>
                <p style="text-align: center; color:green"><?php echo $message; ?></p>
            <?php endif; ?>

            <div class="buttonDisplay sideButton" style="margin-bottom: 30px; width: 100%; gap: 20px;">
                <button type="submit" name="status" value="Available" class="baseBtn" style="flex: 1; padding: 20px;">Set Available</button>
                <button type="submit" name="status" value="Unavailable" class="baseBtn" style="flex: 1; padding: 20px;">Set Unavailable</button>
            </div>

            <div class="form-row" style="margin-bottom: 80px;">
                <input type="text" value="Current driver's status: <?php echo htmlspecialchars($driverStatus); ?>" style="text-align: center; max-width: 400px; font-weight: bold;" readonly>
            </div>

            <div class="buttonDisplay">
                <button type="button" class="baseBtn" onclick="window.location.href='ManageAccount.php';">Back</button>
            </div>
        </form> 
        </div>
    </div>

</body>
</html>