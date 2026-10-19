<?php
session_start();
include 'dbconn.php';

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$driver_email = "";
$driver_data = null;
$current_points = 0;
$trans_result = null;

if (isset($_POST['search_driver'])){
    $driver_email = trim($_POST['email']);
} elseif (isset($_GET['email'])){
    $driver_email = trim($_GET['email']);
}

if (!empty($driver_email)){
    $user_sql = "SELECT UserID, RoleID, Name, Email FROM user WHERE Email = ?";
    $stmt = $conn->prepare($user_sql);
    $stmt->bind_param("s", $driver_email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0){
        echo "<script>alert('Error: Email not found in the system!');</script>";
    }else{
        $user_data = $result->fetch_assoc();
    
        if ($user_data['RoleID'] != 1){
            echo "<script>alert('Invalid Email! This account belongs to a Passenger, not a Volunteer Driver.');</script>";
        }else{
            $driver_data = $user_data;
            $driver_id = $driver_data['UserID'];

            $points_sql = "SELECT VolunteerID, CurrentPoints FROM volunteerdriverprofile WHERE UserID = ?";
            $p_stmt = $conn->prepare($points_sql);
            $p_stmt->bind_param("i", $driver_id);
            $p_stmt->execute();
            $p_result = $p_stmt->get_result();
            
            $volunteer_id = 0;
            if ($row = $p_result->fetch_assoc()){
                $current_points = $row['CurrentPoints'];
                $volunteer_id = $row['VolunteerID'];
            }
            $p_stmt->close();
            if ($volunteer_id > 0){
                $history_sql = "
                    SELECT e.EventName AS Description, e.EventPoints AS Points, ue.CompletionDate AS ActionDate
                    FROM userevent ue
                    JOIN event e ON ue.EventID = e.EventID
                    WHERE ue.VolunteerID = ? AND ue.EventProgressStatus = 'Completed'

                    UNION ALL

                    SELECT r.RewardName AS Description, (r.RewardCost * -1) AS Points, rvd.DateClaimed AS ActionDate
                    FROM rewardvolunteerdriver rvd
                    JOIN reward r ON rvd.RewardID = r.RewardID
                    WHERE rvd.VolunteerID = ?

                    ORDER BY ActionDate DESC
                ";

                $h_stmt = $conn->prepare($history_sql);

                if ($h_stmt === false){
                    die("SQL Error: " . $conn->error);
                }

                $h_stmt->bind_param("ii", $volunteer_id, $volunteer_id);
                $h_stmt->execute();
                $trans_result = $h_stmt->get_result();
            }
        }
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Check Volunteer Points</title>
    <link rel="stylesheet" href="styleR.css">
</head>
<body>

    <h2>Check Volunteer Points</h2>

    <form action="viewdetails.php" method="POST">
        <label>Enter Volunteer Driver Email:</label><br>
        <input type="email" name="email" value="<?php echo htmlspecialchars($driver_email); ?>" required>
        <button type="submit" name="search_driver">View Points</button>
    </form>

    <br><hr><br>

    <?php if ($driver_data): ?>
        <h3>Driver Points Summary</h3>
        <p><strong>Driver Name:</strong> <?php echo htmlspecialchars($driver_data['Name']); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($driver_data['Email']); ?></p>
        <p><strong>Current Reward Points:</strong> <span style="font-size: 20px; color: blue;"><?php echo $current_points; ?> Points</span></p>

        <p>
            <a href="ManageUserRewardPoints.php?id=<?php echo $driver_data['UserID']; ?>">
                <button type="button"><strong>Manage Points for <?php echo htmlspecialchars($driver_data['Name']); ?></strong></button>
            </a>
        </p>

        <br>

        <h3>Point Transaction History</h3>

        <p>
            <a href="SearchUser.php"><button type="button" class="backBtn">Search Another User</button></a>
            <a href="RewardManagerHomepage.html"><button type="button" class="backBtn">Back to Homepage</button></a>
        </p>
        
        <?php if ($trans_result && $trans_result->num_rows > 0): ?>
            <div>
                <table>
                    <thead>
                        <tr>
                            <th>Points</th>
                            <th>Description</th>
                            <th>Date & Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($trans = $trans_result->fetch_assoc()): ?>
                            <tr>
                                <td>
                                    <?php 
                                        $pts = (int)$trans['Points'];
                                        if ($pts > 0){
                                            echo "<span style='color:green; font-weight:bold;'>+$pts</span>";
                                        }else{
                                            echo "<span style='color:red; font-weight:bold;'>$pts</span>";
                                        }
                                    ?>
                                </td>
                                <td><?php echo htmlspecialchars($trans['Description']); ?></td>
                                <td><?php echo htmlspecialchars($trans['ActionDate']); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p>No transaction history found for this driver.</p>
        <?php endif; ?>
    <?php endif; ?>
</body>
</html>

<?php
$conn->close();
?>