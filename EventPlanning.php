<?php
session_start();
include 'dbconn.php';

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$user_role = $_SESSION['role_id'] ?? 'guest' ;
$back_url = ($user_role === 4) ? 'RewardManagerHomepage.html' : 'index.php';

if (isset($_POST['add_event']) && $user_role === 4){
    $event_name = trim($_POST['event_name']);
    $event_points = intval($_POST['event_points']);
    $start_date = $_POST['event_start_date'];
    $end_date = $_POST['event_end_date'];
    $required_rides = intval($_POST['event_required_ride']);

    try{
        $insert_sql = "INSERT INTO event (EventName, EventPoints, EventStartDate, EventEndDate, EventRequiredRide) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($insert_sql);
        $stmt->bind_param("sissi", $event_name, $event_points, $start_date, $end_date, $required_rides);
        $stmt->execute();
        $stmt->close();

        echo "<script>alert('Event created successfully!'); window.location.href='EventPlanning.php';</script>";
        exit();
    }catch (mysqli_sql_exception $e){
        echo "<script>alert('Failed to create event: " . addslashes($e->getMessage()) . "');</script>";
    }
}

if ($user_role === 4 && isset($_POST['delete_event'])){
    $event_id = intval($_POST['event_id']);

    $conn->begin_transaction();

    try{
        $delete_child_sql = "DELETE FROM userevent WHERE EventID = ?";
        $stmt_child = $conn->prepare($delete_child_sql);
        $stmt_child->bind_param("i", $event_id);
        $stmt_child->execute();
        $stmt_child->close();

        $delete_sql = "DELETE FROM event WHERE EventID = ?";
        $stmt = $conn->prepare($delete_sql);
        $stmt->bind_param("i", $event_id);
        $stmt->execute();
        $stmt->close();

        $conn->commit();

        echo "<script>alert('Event deleted successfully!'); window.location.href='EventPlanning.php';</script>";
        exit();
    }catch (mysqli_sql_exception $e){
        $conn->rollback();
        echo "<script>alert('Failed to delete event: " . addslashes($e->getMessage()) . "');</script>";
    }
}

try{
    $events_result = $conn->query("SELECT EventID, EventName, EventPoints, EventStartDate, EventEndDate, EventRequiredRide FROM event ORDER BY EventStartDate ASC");
}catch (mysqli_sql_exception $e){
    die("Error fetching events: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Volunteer Events</title>
    <link rel="stylesheet" href="styleR.css">
</head>
<body>

    <h2>Manage Events</h2>
    
    <button type="button" class="backBtn" onclick="window.location.href='<?php echo htmlspecialchars($back_url); ?>';">Back to Homepage</button><br>

    <?php if ($user_role === 4): ?>
        <h3>Create New Event</h3>
        <form action="EventPlanning.php" method="POST">
            <p>
                <label>Event Name:</label><br>
                <input type="text" name="event_name" required>
            </p>
            <p>
                <label>Points Rewarded:</label><br>
                <input type="number" name="event_points" required min="1">
            </p>
            <p>
                <label>Start Date:</label><br>
                <input type="date" name="event_start_date" required>
            </p>
            <p>
                <label>End Date:</label><br>
                <input type="date" name="event_end_date" required>
            </p>
            <p>
                <label>Required Rides to Complete:</label><br>
                <input type="number" name="event_required_ride" required min="1">
            </p>
            <button type="submit" name="add_event">Create Event</button>
        </form>
        <br><hr><br>
    <?php endif; ?>

    <h3>Available Events</h3>

    <?php if ($events_result && $events_result->num_rows > 0): ?>
        <table border="1" cellpadding="8" cellspacing="0" width="80%">
            <thead>
                <tr>
                    <th>Event Name</th>
                    <th>Earnable Points</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Required Rides</th>
                    <?php if ($user_role === 4): ?>
                        <th>Action</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $events_result->fetch_assoc()): ?>
                    <tr>
                        <td><strong><?php echo htmlspecialchars($row['EventName']); ?></strong></td>
                        <td><?php echo number_format($row['EventPoints']); ?> Points</td>
                        <td><?php echo htmlspecialchars($row['EventStartDate']); ?></td>
                        <td><?php echo htmlspecialchars($row['EventEndDate']); ?></td>
                        <td><?php echo $row['EventRequiredRide']; ?> Rides</td>

                        <?php if ($user_role === 4): ?>
                            <td>
                                <form action="EventPlanning.php" method="POST" onsubmit="return confirm('Are you sure you want to delete this event?');">
                                    <input type="hidden" name="event_id" value="<?php echo $row['EventID']; ?>">
                                    <button type="submit" name="delete_event">Delete</button>
                                </form>
                            </td>
                        <?php endif; ?>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No active events found at this time.</p>
    <?php endif; ?>
</body>
</html>

<?php
$conn->close();
?>