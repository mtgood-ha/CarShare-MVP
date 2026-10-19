<?php
    session_start();
    include 'dbconn.php';

    $user_role = isset($_SESSION['role_id']) ? $_SESSION['role_id'] : 'guest';
    $back_url = ($user_role === 4) ? 'RewardManagerHomepage.html' : 'MainPageVD.php';

    $query = "SELECT v.UserID, v.VolunteerID, v.NumberOfRidesDone, u.UserID, u.RoleID, u.Username
              FROM volunteerdriverprofile v JOIN user u ON v.UserID = u.UserID
              ORDER BY NumberOfRidesDone DESC 
              LIMIT 50";

    $result = mysqli_query($conn, $query);

    if(!$result){
        die("Error fetching leaderboard: " . mysqli_error($conn));
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Volunteer Leaderboard</title>
    <link rel="stylesheet" href="styleR.css">
</head>
<body>

    <h2>Top 50 Volunteer Leaderboard</h2>

    <?php if (mysqli_num_rows($result) > 0): ?>
        <table border="1" cellpadding="8" cellspacing="0" width="60%">
            <thead>
                <tr>
                    <th>Rank</th>
                    <th>Volunteer Username</th>
                    <th>Total Rides Completed</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $rank = 1;
                while ($row = mysqli_fetch_assoc($result)): 
                ?>
                    <tr>
                        <td><strong><?php echo $rank++; ?></strong></td>
                        <td><?php echo htmlspecialchars($row['Username']); ?></td>
                        <td><?php echo number_format($row['NumberOfRidesDone']); ?> Rides</td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No volunteer rankings available at this time.</p>
    <?php endif; ?>

    <br><hr><br>

    <?php if ($user_role === 4): ?>
        <button type="button" class="baseBtn" onclick="window.location.href='ExportLeaderboard.php';">Export Leaderboard</button><br>
        <br><br>
    <?php endif; ?>

    <button type="button" class="backBtn" onclick="window.location.href='<?php echo $back_url; ?>';">Back to Homepage</button><br>

</body>
</html>

<?php
    mysqli_close($conn);
?>