<?php
session_start();
include 'dbconn.php';

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

if ($conn->connect_error){
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['add_reward'])){
    $reward_name = trim($_POST['reward_name']);
    $reward_cost = intval($_POST['reward_cost']);

    if (!empty($reward_name) && $reward_cost > 0){
        try{
            $insert_sql = "INSERT INTO reward (RewardName, RewardCost) VALUES (?, ?)";
            $stmt = $conn->prepare($insert_sql);
            $stmt->bind_param("si", $reward_name, $reward_cost);
            $stmt->execute();
            $stmt->close();
            echo "<script>alert('New reward posted successfully!'); window.location.href='ManageRewardSystem.php';</script>";
            exit();
        }catch (mysqli_sql_exception $e){
            echo "<script>alert('Failed to post reward: " . addslashes($e->getMessage()) . "');</script>";
        }
    }else{
        echo "<script>alert('Please enter a valid reward name and a point cost greater than 0.');</script>";
    }
}

if (isset($_POST['delete_reward'])){
    $reward_id = intval($_POST['reward_id']);

    try{
        $delete_sql = "DELETE FROM reward WHERE RewardID = ?";
        $stmt = $conn->prepare($delete_sql);
        $stmt->bind_param("i", $reward_id);
        $stmt->execute();
        $stmt->close();
        echo "<script>alert('Reward removed successfully!'); window.location.href='ManageRewardSystem.php';</script>";
        exit();
    }catch (mysqli_sql_exception $e){
        echo "<script>alert('Cannot delete this reward: It is already linked to driver redemption history.');</script>";
    }
}

try{
    $rewards_sql = "SELECT RewardID, RewardName, RewardCost FROM reward ORDER BY RewardID ASC";
    $result = $conn->query($rewards_sql);
}catch (mysqli_sql_exception $e){
    die("Error loading rewards: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Manage Reward System</title>
    <link rel="stylesheet" href="styleR.css">
</head>
<body>

    <h2>Manage Reward System</h2>
    <button type="button" class="backBtn" onclick="window.location.href='RewardSystemHomepage.html';">Back to Homepage</button><br> 

    <h3>Add New Reward Item</h3>
    <form action="ManageRewardSystem.php" method="POST">
        <p>
            <label>Reward Name:</label><br>
            <input type="text" name="reward_name" required>
        </p>
        <p>
            <label>Reward Point Cost:</label><br>
            <input type="number" name="reward_cost" required min="1">
        </p>
        <button type="submit" name="add_reward">Add Reward</button>
    </form>

    <br><hr><br>

    <h3>Current Reward Items</h3>

    <?php if ($result && $result->num_rows > 0): ?>
        <table border="1" cellpadding="8" cellspacing="0" width="80%">
            <thead>
                <tr>
                    <th>Reward ID</th>
                    <th>Reward Name</th>
                    <th>Point Cost</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['RewardID']; ?></td>
                        <td><?php echo htmlspecialchars($row['RewardName']); ?></td>
                        <td><?php echo $row['RewardCost']; ?> Points</td>
                        <td>
                            <form action="ManageRewardSystem.php" method="POST" onsubmit="return confirm('Are you sure you want to delete this reward from the system?');">
                                <input type="hidden" name="reward_id" value="<?php echo $row['RewardID']; ?>">
                                <button type="submit" name="delete_reward" style="color: red;">Delete Reward</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No rewards posted yet. Use the form above to add your first item.</p>
    <?php endif; ?>

</body>
</html>

<?php
$conn->close();
?>