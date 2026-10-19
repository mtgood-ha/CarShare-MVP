<?php

include 'dbconn.php';
session_start();

if ($conn->connect_error){
    die("Connection failed: " . $conn->connect_error);
}

try{
    $rewards_sql = "SELECT RewardID, RewardName, RewardCost FROM reward ORDER BY RewardID ASC";
    $result = $conn->query($rewards_sql);
}catch (mysqli_sql_exception $e){
    die("Error Loading Rewards: " . $e->getMessage());
}

$user_role = $_SESSION['role_id'] ?? 'guest'; 

if ($user_role === 4){
    $back_url = 'RewardSystemHomepage.html';
}else{
    $back_url = 'index.php';
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Reward System</title>
    <link rel="stylesheet" href="styleR.css">
</head>
<body>
    <h2>Existing Rewards</h2>

    <button type="button" class="backBtn" onclick="window.location.href=href='<?php echo $back_url; ?>';">Back to Homepage</button><br>

    <?php if ($result && $result->num_rows > 0):?>
        <table>
            <thead>
                <tr>
                    <th>Reward ID</th>
                    <th>Reward Name</th>
                    <th>Point Cost</th>
                </tr>
            </thead>
            <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['RewardID']; ?></td>
                        <td><?php echo htmlspecialchars($row['RewardName']); ?></td>
                        <td><?php echo $row['RewardCost']; ?> Points</td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No rewards posted yet. Use the form above to add your first item.</p>
    <?php endif; ?>
</body>
</html>

