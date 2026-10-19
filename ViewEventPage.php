<?php
    session_start(); 
    include 'dbconn.php'; 
    date_default_timezone_set('Asia/Kuala_Lumpur');

    $user_id = $_SESSION['user_id']; 
    $volunteerQuery = "SELECT VolunteerID FROM volunteerdriverprofile WHERE UserID = '$user_id'";
    $volunteerExecute = mysqli_query($conn, $volunteerQuery);

    if (mysqli_num_rows($volunteerExecute) > 0) {
        $Vrow = mysqli_fetch_assoc($volunteerExecute);
        $volunteer_id = (int)$Vrow['VolunteerID'];
    } else {
        die("Error. No volunteer profile found.");
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['event_id'])) {
        $event_id = (int)$_POST['event_id'];
        $getEventStatusQuery = "SELECT EventProgressStatus FROM userevent WHERE EventID = '$event_id' AND VolunteerID = '$volunteer_id'";
        $getEventStatusQueryExecute = mysqli_query($conn, $getEventStatusQuery);

        if (mysqli_num_rows($getEventStatusQueryExecute) > 0) {
            $checkIfEventComplete = mysqli_fetch_assoc($getEventStatusQueryExecute);
            if ($checkIfEventComplete['EventProgressStatus'] === 'Completed') {
                echo "<script>alert('You have already claimed points for this event!'); window.location.href='ViewEventPage.php';</script>"; 
                exit();
            }
        }

        $getEvents = "SELECT EventID, EventRequiredRide, EventPoints, EventStartDate, EventEndDate FROM event WHERE EventID = '$event_id'";
        $eventExecute = mysqli_query($conn, $getEvents);

        if (mysqli_num_rows($eventExecute) > 0) {
            $eventDetails = mysqli_fetch_assoc($eventExecute);
     
            $eventStart = $eventDetails['EventStartDate'];
            $eventEnd = $eventDetails['EventEndDate'];
            $requiredRides = (int)$eventDetails['EventRequiredRide'];
            $pointsToAdd = (int)$eventDetails['EventPoints'];

            $ridesQuery = "SELECT COUNT(*) AS TotalCompletedRides 
                          FROM rides r 
                          JOIN riderequest rr ON r.RideRequestID = rr.RideRequestID
                          WHERE rr.VolunteerID = '$volunteer_id'
                          AND r.RideStatus = 'Completed' 
                          AND DATE(r.DateOfRides) BETWEEN '$eventStart' AND '$eventEnd'";
                          
            $ridesExecute = mysqli_query($conn, $ridesQuery);
            
            $TotalRideDone = 0;
            if (mysqli_num_rows($ridesExecute) > 0) {
                $ride_data = mysqli_fetch_assoc($ridesExecute);
                $TotalRideDone = (int)$ride_data['TotalCompletedRides']; 
            }
            
            if ($TotalRideDone >= $requiredRides) {
                $today = date('Y-m-d');

                $insertUserEvent = "INSERT INTO userevent (EventID, VolunteerID, EventProgressStatus, CompletionDate) 
                               VALUES ('$event_id', '$volunteer_id', 'Completed', '$today') 
                               ON DUPLICATE KEY UPDATE EventProgressStatus = 'Completed', CompletionDate = '$today'";
                
                if (mysqli_query($conn, $insertUserEvent)) {
                    $updatePointsQuery = "UPDATE volunteerdriverprofile SET CurrentPoints = CurrentPoints + '$pointsToAdd' WHERE VolunteerID = '$volunteer_id'";

                    if (mysqli_query($conn, $updatePointsQuery)) {
                        echo "<script>alert('Successful! Completed {$TotalRideDone}/{$requiredRides} rides. Earned {$pointsToAdd} points!'); window.location.href='ViewEventPage.php';</script>";
                        exit(); 
                    } else {
                        echo "Error updating points: " . mysqli_error($conn);
                    }
                } else {
                    echo "Error updating event progress: " . mysqli_error($conn);
                }
            } else {
                echo "<script>alert('Requirements not met! Completed: {$TotalRideDone} / {$requiredRides} required rides.'); window.location.href='ViewEventPage.php';</script>";
                exit();
            }
        } else {
            die("Event not found.");
        }
    }

    $query = "SELECT e.EventID, e.EventName, e.EventPoints, e.EventStartDate, e.EventEndDate, e.EventRequiredRide, 
            ue.EventProgressStatus, 
                    (SELECT COUNT(*) 
                    FROM rides r 
                    JOIN riderequest rr ON r.RideRequestID = rr.RideRequestID 
                    WHERE rr.VolunteerID = '$volunteer_id' 
                    AND r.RideStatus = 'Completed' 
                    AND DATE(r.DateOfRides) BETWEEN e.EventStartDate AND e.EventEndDate
                    ) AS CurrentCompletedRides 
        FROM event e 
        LEFT JOIN userevent ue ON e.EventID = ue.EventID AND ue.VolunteerID = '$volunteer_id' 
        ORDER BY e.EventStartDate ASC";
    
    $events_result = mysqli_query($conn, $query);

    if (!$events_result) {
        die("Error: " . mysqli_error($conn));
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Volunteer Events & Rewards</title>
    <link rel="stylesheet" href="styleV.css">
</head>
<body>
    <div class="center-wrapper" style="max-width: 950px;">
        <h2>Volunteer Events & Rewards Program</h2>

        <div class="achievement-box textBorder" style="min-height: auto; width: 100%;">
            <h3 style="color: rgb(43, 95, 138); margin-bottom: 25px;">Ongoing Events</h3>

            <?php if ($events_result && $events_result->num_rows > 0): ?>
                <div style="overflow-x: auto; width: 100%;">
                    <table border="1" cellpadding="12" cellspacing="0" style="width: 100%; border-collapse: collapse; text-align: center; border-color: #ccc;">
                        <thead style="background-color: rgba(43, 95, 138, 0.18);">
                            <tr>
                                <th>Event Name</th>
                                <th>Points</th>
                                <th>Duration</th>
                                <th>Progress (Completed / Needed)</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $events_result->fetch_assoc()): ?>
                                <tr>
                                    <td><strong><?php echo htmlspecialchars($row['EventName']); ?></strong></td>
                                    <td><strong>+<?php echo number_format($row['EventPoints']); ?></strong></td>
                                    <td>
                                        <small><?php echo htmlspecialchars($row['EventStartDate']); ?> to <br><?php echo htmlspecialchars($row['EventEndDate']); ?></small>
                                    </td>
                                    <td>
                                        <strong><?php echo (int)$row['CurrentCompletedRides']; ?></strong> / <?php echo (int)$row['EventRequiredRide']; ?> Rides
                                    </td>
                                    <td>
                                        <?php if ($row['EventProgressStatus'] === 'Completed'): ?>
                                            <span style="color: green; font-weight: bold;">Completed</span>
                                        <?php elseif (date('Y-m-d') > $row['EventEndDate']): ?>
                                            <span style="color: #525a5f; font-weight: bold;">Overdue</span>
                                        <?php else: ?>
                                            <span style="color: #853532; font-weight: bold;">In Progress</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($row['EventProgressStatus'] === 'Completed'): ?>
                                            <button disabled class="baseBtn" style="opacity: 0.6; cursor: not-allowed;">Claimed</button>
                                        <?php else: ?>
                                            <form method="POST" action="" style="margin: 0;">
                                                <input type="hidden" name="event_id" value="<?php echo $row['EventID']; ?>">
                                                <button type="submit" class="baseBtn" style="padding: 6px 12px; font-size: 14px;">Claim Points</button>
                                            </form>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p style="color: #000000; margin-top: 20px;">No active events found at this time.</p>
            <?php endif; ?>
        </div>

        <div class="buttonDisplay" style="margin-top: 20px;">
            <a href="MainPageVD.php" class="baseBtn" role="button">Back to Dashboard</a>
        </div>
    </div>
</body>
</html>