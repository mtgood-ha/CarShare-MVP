<?php
    session_start();
    include 'dbconn.php';
    date_default_timezone_set('Asia/Kuala_Lumpur');
    
    $popupMsg = "";
    $currentPoints = 0; 
    $user_id = $_SESSION["user_id"]; 

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reward_id'], $_POST['cost'])) {
        $reward_id = mysqli_real_escape_string($conn, $_POST['reward_id']);
        $cost = (int)$_POST['cost'];
        $checkQuery = "SELECT CurrentPoints, VolunteerID FROM volunteerdriverprofile WHERE UserID = '$user_id'";
        $checkPoints = mysqli_query($conn, $checkQuery);
        
        if (mysqli_num_rows($checkPoints) > 0) {
            $row = mysqli_fetch_assoc($checkPoints);
            $currentPoints = (int)$row['CurrentPoints'];
            $volunteer_id = (int)$row['VolunteerID'];
            
            if ($currentPoints >= $cost) {
                $newPoints = $currentPoints - $cost;
                $updateQuery = "UPDATE volunteerdriverprofile SET CurrentPoints = '$newPoints' WHERE VolunteerID = '$volunteer_id'";
                if (mysqli_query($conn, $updateQuery)) { 
                    $current_date = date('Y-m-d');                
                    $insert = "INSERT INTO rewardvolunteerdriver (RewardID, VolunteerID, DateClaimed) VALUES ('$reward_id', '$volunteer_id', '$current_date')";
                    if (mysqli_query($conn, $insert)) {
                        $popupMsg = "<p style='color: green; text-align: center; font-weight: bold;'>Reward redeemed successfully!</p>";
                    } else {
                        $popupMsg = "<p style='color: red; text-align: center;'>Error inserting data. " . mysqli_error($conn) . "</p>";
                    }
                } else {
                    $popupMsg = "<p style='color: red; text-align: center;'>error updating points!.</p>";
                }
            } else {
                $popupMsg = "<p style='color: red; text-align: center;'>Not enough points to redeem this reward.</p>";
            }
        }
    }

    $pointsQuery = "SELECT CurrentPoints FROM volunteerdriverprofile WHERE UserID = '$user_id'";
    $getPoints = mysqli_query($conn, $pointsQuery);
    
    if (mysqli_num_rows($getPoints) > 0) {
        $row = mysqli_fetch_assoc($getPoints);
        $currentPoints = $row['CurrentPoints'];
    }
    $rewardsQuery = "SELECT RewardID, RewardName, RewardCost FROM reward ORDER BY RewardID ASC";
    $result = mysqli_query($conn, $rewardsQuery);
    
    if(!$result){
        die("Error retreiving rewards! " . mysqli_error($conn));
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Reward Page</title>
    <link rel="stylesheet" href="styleV.css?v=2">
</head>
<body>
    <div class="center-wrapper" style="max-width: 800px;">
        <h2>Reward Page</h2>
        
        <?php if(!empty($popupMsg)) echo $popupMsg; ?>
        
        <div class="textBorder centerText" style="padding: 20px; margin-bottom: 25px; min-height: auto;">
            <p style="margin: 0; font-weight: bold; font-size: 1.1rem; color: #434242;">Your Points: <?php echo htmlspecialchars($currentPoints); ?></p>
        </div>

        <div class="achievement-box textBorder" style="min-height: auto; padding: 25px 30px;">
            <div style="display: flex; flex-direction: column; gap: 20px; width: 100%;">
                
                <?php if ($result && $result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                    
                        <div style="display: flex; flex-direction: row; align-items: center; justify-content: center; gap: 25px; flex-wrap: wrap; width: 100%;">
                           
                            <div class="textBorder centerText" style="background-color: #ffffff; padding: 25px 20px; flex: 1 1 200px; flex-direction: column;">
                                <h4 style="margin: 0 0 10px 0; font-size: 1.2rem; color: #313131; font-weight: normal; text-align: center;">
                                    <?php echo htmlspecialchars($row['RewardName']); ?>
                                </h4>
                                <p style="margin: 0; font-size: 1rem; color: #666; text-align: center;">
                                    <?php echo htmlspecialchars($row['RewardCost']); ?> Points
                                </p>
                            </div>

                            <form method="POST" style="" onsubmit="return checkPoints(<?php echo $currentPoints; ?>, <?php echo htmlspecialchars($row['RewardCost']); ?>);">
                                <input type="hidden" name="reward_id" value="<?php echo htmlspecialchars($row['RewardID']); ?>">
                                <input type="hidden" name="cost" value="<?php echo htmlspecialchars($row['RewardCost']); ?>">                
                                <button type="submit" class="baseBtn" style="width: 120px; padding: 12px 15px;"> Redeem </button>
                            </form>

                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p style="text-align: center; margin-top: 20px;">No rewards available at the moment.</p>
                <?php endif; ?>
            </div>
            </div>

        <div class="buttonDisplay" style="margin-top: 20px;">
            <button type="button" class="baseBtn" onclick="window.location.href='MainPageVD.php';">Back</button>
        </div>
    </div>
    
    <script>
        function checkPoints(currentPoints, requiredPoints) {
            if (currentPoints < requiredPoints) {
                alert("Not enough points to redeem this reward.");
                return false; 
            }
            return true;
        }
    </script>
</body>
</html>