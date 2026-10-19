<?php
session_start();
include 'dbconn.php';

if ($conn->connect_error){
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_GET['id']) || empty($_GET['id'])){
    echo "<script>alert('No driver selected!'); window.location.href='SearchUser.php';</script>";
    exit();
}

$user_id = intval($_GET['id']);

try{
    $driver_sql = "SELECT u.Name, u.Email, v.VolunteerID, v.CurrentPoints 
                   FROM user u 
                   JOIN volunteerdriverprofile v ON u.UserID = v.UserID 
                   WHERE u.UserID = ?";
    $stmt = $conn->prepare($driver_sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $driver_res = $stmt->get_result();

    if ($driver_res->num_rows === 0){
        echo "<script>alert('Error: This user does not have a profile in volunteerdriverprofile!'); window.location.href='SearchUser.php';</script>";
        exit();
    }

    $driver = $driver_res->fetch_assoc();
    $volunteer_id = $driver['VolunteerID'];
    $stmt->close();

}catch (mysqli_sql_exception $e){
    die("Database Query Error: " . $e->getMessage());
}

if (isset($_POST['adjust_points'])){
    $points_input = intval($_POST['points']);
    $type = $_POST['type'];

    if ($points_input === 0){
        echo "<script>alert('Please enter a non-zero number for points.');</script>";
    } else {

        if ($type === 'deduct'){
            $final_change = -abs($points_input); 
        }else{
            $final_change = $points_input; 
        }

        try{
            $up_sql = "UPDATE volunteerdriverprofile SET CurrentPoints = CurrentPoints + ? WHERE VolunteerID = ?";
            $u_stmt = $conn->prepare($up_sql);
            $u_stmt->bind_param("ii", $final_change, $volunteer_id);
            $u_stmt->execute();
            $u_stmt->close();

            echo "<script>alert('Points updated successfully!'); window.location.href='ManageUserRewardPoints.php?id=$user_id';</script>";
            exit();
        }catch (mysqli_sql_exception $e){
            echo "<script>alert('Failed to update points: " . addslashes($e->getMessage()) . "');</script>";
        }
    }
}

if (isset($_POST['delete_record'])){
    $composite_id = $_POST['record_id'];

    if (strpos($composite_id, ':') !== false){
        list($source, $record_id) = explode(':', $composite_id);
        $record_id = intval($record_id);

        try{
            if ($source === 'event'){
                $check_sql = "SELECT e.EventPoints FROM userevent ue JOIN event e ON ue.EventID = e.EventID WHERE ue.UserEventID = ? AND ue.VolunteerID = ?";
                $chk_stmt = $conn->prepare($check_sql);
                $chk_stmt->bind_param("ii", $record_id, $volunteer_id);
                $chk_stmt->execute();
                $res = $chk_stmt->get_result();

                if ($res->num_rows === 1){
                    $row = $res->fetch_assoc();
                    $db_points = intval($row['EventPoints']);
                    $chk_stmt->close();

                    $del_sql = "DELETE FROM userevent WHERE UserEventID = ? AND VolunteerID = ?";
                    $d_stmt = $conn->prepare($del_sql);
                    $d_stmt->bind_param("ii", $record_id, $volunteer_id);
                    $d_stmt->execute();
                    $d_stmt->close();

                    $up_sql = "UPDATE volunteerdriverprofile SET CurrentPoints = CurrentPoints - ? WHERE VolunteerID = ?";
                    $u_stmt = $conn->prepare($up_sql);
                    $u_stmt->bind_param("ii", $db_points, $volunteer_id);
                    $u_stmt->execute();
                    $u_stmt->close();
                }

            }elseif ($source === 'reward'){
                $check_sql = "SELECT r.RewardCost FROM rewardvolunteerdriver rvd JOIN reward r ON rvd.RewardID = r.RewardID WHERE rvd.RewardVolunteerDriverID = ? AND rvd.VolunteerID = ?";
                $chk_stmt = $conn->prepare($check_sql);
                $chk_stmt->bind_param("ii", $record_id, $volunteer_id);
                $chk_stmt->execute();
                $res = $chk_stmt->get_result();

                if ($res->num_rows === 1){
                    $row = $res->fetch_assoc();
                    $db_points = intval($row['RewardCost']);
                    $chk_stmt->close();

                    $del_sql = "DELETE FROM rewardvolunteerdriver WHERE RewardVolunteerDriverID = ? AND VolunteerID = ?";
                    $d_stmt = $conn->prepare($del_sql);
                    $d_stmt->bind_param("ii", $record_id, $volunteer_id);
                    $d_stmt->execute();
                    $d_stmt->close();

                    $up_sql = "UPDATE volunteerdriverprofile SET CurrentPoints = CurrentPoints + ? WHERE VolunteerID = ?";
                    $u_stmt = $conn->prepare($up_sql);
                    $u_stmt->bind_param("ii", $db_points, $volunteer_id);
                    $u_stmt->execute();
                    $u_stmt->close();
                }
            }

            echo "<script>alert('Record verified, deleted, and points recalculated!'); window.location.href='ManageUserRewardPoints.php?id=$user_id';</script>";
            exit();

        }catch (mysqli_sql_exception $e){
            echo "<script>alert('Delete Failed: " . addslashes($e->getMessage()) . "');</script>";
        }
    }
}

$history_sql = "
    SELECT CONCAT('event:', ue.UserEventID) AS CompositeID, e.EventName AS Description, e.EventPoints AS Points, ue.CompletionDate AS ActionDate
    FROM userevent ue
    JOIN event e ON ue.EventID = e.EventID
    WHERE ue.VolunteerID = ? AND ue.EventProgressStatus = 'Completed'

    UNION ALL

    SELECT CONCAT('reward:', rvd.RewardVolunteerDriverID) AS CompositeID, r.RewardName AS Description, (r.RewardCost * -1) AS Points, 
    rvd.DateClaimed AS ActionDate
    FROM rewardvolunteerdriver rvd
    JOIN reward r ON rvd.RewardID = r.RewardID
    WHERE rvd.VolunteerID = ?

    ORDER BY ActionDate DESC";

$h_stmt = $conn->prepare($history_sql);
if ($h_stmt === false){
    die("SQL Prepare Error: " . $conn->error);
}

$h_stmt->bind_param("ii", $volunteer_id, $volunteer_id);
$h_stmt->execute();
$trans_result = $h_stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Reward Points</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styleR.css">
</head>
<body>

    <h2>Manage User Reward Points</h2>
    <br><br>
    <p>
        <a href="viewdetails.php?email=<?php echo urlencode($driver['Email']); ?>"><button type="button" class="backBtn">Back to View Details</button></a>
        <a href="RewardManagerHomepage.html"><button type="button" class="backBtn">Back to Homepage</button></a>
    </p>

    <p><strong>Driver:</strong> <?php echo htmlspecialchars($driver['Name']); ?> (<?php echo htmlspecialchars($driver['Email']); ?>)</p>
    <p><strong>Current Points:</strong> <span style="font-size: 20px; color: blue;"><?php echo $driver['CurrentPoints']; ?> Points</span></p>

    <br><hr><br>

    <h3>Manually Adjust Points</h3>
    <form action="ManageUserRewardPoints.php?id=<?php echo $user_id; ?>" method="POST">
        <label for="type">Action:</label>
        <select name="type" id="type" required>
            <option value="add">Add Points</option>
            <option value="deduct">Deduct Points</option>
        </select>

        <label for="points">Points Amount:</label>
        <input type="number" name="points" id="points" required placeholder="e.g. 50 or -50">

        <button type="submit" name="adjust_points">Update Points</button>
    </form>

    <br><hr><br>

    <h3>Point Activity History</h3>

    <?php if ($trans_result && $trans_result->num_rows > 0): ?>
        <div>
            <table>
                <thead>
                    <tr>
                        <th>Points</th>
                        <th>Description</th>
                        <th>Date & Time</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($trans = $trans_result->fetch_assoc()): ?>
                        <tr>
                            <td>
                                <?php 
                                    $pts = (int)$trans['Points'];
                                    if ($pts > 0) {
                                        echo "<span style='color:green; font-weight:bold;'>+$pts</span>";
                                    } else {
                                        echo "<span style='color:red; font-weight:bold;'>$pts</span>";
                                    }
                                ?>
                            </td>
                            <td><?php echo htmlspecialchars($trans['Description']); ?></td>
                            <td><?php echo htmlspecialchars($trans['ActionDate']); ?></td>
                            <td>
                                <form action="ManageUserRewardPoints.php?id=<?php echo $user_id; ?>" method="POST" onsubmit="return confirm('Are you sure you want to delete this record? Points will be recalculated automatically.');">
                                    <input type="hidden" name="record_id" value="<?php echo $trans['CompositeID']; ?>">
                                    <button type="submit" name="delete_record" style="color: red;">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <p>No transactions recorded yet for this user.</p>
    <?php endif; ?>

</body>
</html>

<?php
$h_stmt->close();
$conn->close();
?>