<?php
session_start();
include 'dbconn.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 3) {
    header("Location: LoginPage.html");
    exit();
}

if (!isset($_GET['penalty_id'])) {
    header("Location: ManagePenalty.php");
    exit();
}

$penaltyID = intval($_GET['penalty_id']);
$adminUserID = $_SESSION['user_id'];
$message = "";

$penaltyQuery = "SELECT PenaltyID, RideID, UserID, PenaltyDate,
                        PenaltyReason, PenaltyStatus
                 FROM penaltysystem
                 WHERE PenaltyID = $penaltyID";

$penaltyResult = mysqli_query($conn, $penaltyQuery);
$penalty = mysqli_fetch_assoc($penaltyResult);

if (!$penalty) {
    header("Location: ManagePenalty.php");
    exit();
}

$userID = $penalty['UserID'];
$rideID = $penalty['RideID'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_POST['update_status'])) {

        $newStatus = $_POST['penalty_status'];

        if ($newStatus == "Ongoing" ||
            $newStatus == "Active" ||
            $newStatus == "Completed") {

            $updateQuery = "UPDATE penaltysystem
                            SET PenaltyStatus = '$newStatus'
                            WHERE PenaltyID = $penaltyID";

            if (mysqli_query($conn, $updateQuery)) {

                $message = "Penalty status updated successfully.";

                $actionText = "Updated Penalty ID $penaltyID status to $newStatus for User ID $userID";
                $actionText = mysqli_real_escape_string($conn, $actionText);

                $logQuery = "INSERT INTO adminactivitylog
                             (ActionType, TimeStamp, UserID, ActionDate)
                             VALUES
                             ('$actionText', CURTIME(), $adminUserID, CURDATE())";

                mysqli_query($conn, $logQuery);
            }
        }
    }

    if (isset($_POST['user_action'])) {

        $action = $_POST['user_action'];

        if ($action == "Ban") {
            $newAccountStatus = "Banned";
        } else {
            $newAccountStatus = "";
        }

        if ($newAccountStatus != "") {

            $updateUserQuery = "UPDATE user
                                SET AccountStatus = '$newAccountStatus'
                                WHERE UserID = $userID";

            if (mysqli_query($conn, $updateUserQuery)) {

                $message = "User account updated to $newAccountStatus.";

                $actionText = "Banned user account for User ID $userID from Penalty ID $penaltyID";
                $actionText = mysqli_real_escape_string($conn, $actionText);

                $logQuery = "INSERT INTO adminactivitylog
                             (ActionType, TimeStamp, UserID, ActionDate)
                             VALUES
                             ('$actionText', CURTIME(), $adminUserID, CURDATE())";

                mysqli_query($conn, $logQuery);
            }
        }
    }

    $penaltyResult = mysqli_query($conn, $penaltyQuery);
    $penalty = mysqli_fetch_assoc($penaltyResult);
}

$rideQuery = "SELECT RideID, RideRequestID, DateOfRides, RideStatus,
                     CarPlateNumber, StartTime, EndTime
              FROM rides
              WHERE RideID = $rideID";

$rideResult = mysqli_query($conn, $rideQuery);
$ride = mysqli_fetch_assoc($rideResult);

$reportQuery = "SELECT ReportID, ReportDate, ReportDescription
                FROM report
                WHERE RideID = $rideID
                ORDER BY ReportDate DESC
                LIMIT 1";

$reportResult = mysqli_query($conn, $reportQuery);
$report = mysqli_fetch_assoc($reportResult);

$feedbackResult = null;

if ($ride) {

    $rideRequestID = $ride['RideRequestID'];

    $feedbackQuery = "SELECT FeedbackID, StarRating, Comment, FeedbackDirection
                      FROM feedback
                      WHERE RideID = $rideRequestID";

    $feedbackResult = mysqli_query($conn, $feedbackQuery);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Penalty Details</title>

    <link rel="stylesheet" href="styleA.css">
    <script src="Admin.js"></script>
</head>

<body>

    <div class="center-wrapper">

        <h1>Penalty Details</h1>

        <?php if ($message != "") { ?>
            <p><?php echo htmlspecialchars($message); ?></p>
        <?php } ?>

        <h2>Penalty Information</h2>

        <table>

            <tr>
                <th>Penalty ID</th>
                <td><?php echo htmlspecialchars($penalty['PenaltyID']); ?></td>
            </tr>

            <tr>
                <th>User ID</th>
                <td><?php echo htmlspecialchars($penalty['UserID']); ?></td>
            </tr>

            <tr>
                <th>Ride ID</th>
                <td><?php echo htmlspecialchars($penalty['RideID']); ?></td>
            </tr>

            <tr>
                <th>Penalty Date</th>
                <td><?php echo htmlspecialchars($penalty['PenaltyDate']); ?></td>
            </tr>

            <tr>
                <th>Reason</th>
                <td><?php echo htmlspecialchars($penalty['PenaltyReason']); ?></td>
            </tr>

            <tr>
                <th>Status</th>
                <td><?php echo htmlspecialchars($penalty['PenaltyStatus']); ?></td>
            </tr>

        </table>

        <h2>Related Ride</h2>

        <table>

            <?php if ($ride) { ?>

                <tr>
                    <th>Ride ID</th>
                    <td><?php echo htmlspecialchars($ride['RideID']); ?></td>
                </tr>

                <tr>
                    <th>Date</th>
                    <td><?php echo htmlspecialchars($ride['DateOfRides']); ?></td>
                </tr>

                <tr>
                    <th>Status</th>
                    <td><?php echo htmlspecialchars(ucfirst($ride['RideStatus'])); ?></td>
                </tr>

                <tr>
                    <th>Vehicle</th>
                    <td><?php echo htmlspecialchars($ride['CarPlateNumber']); ?></td>
                </tr>

                <tr>
                    <th>Start Time</th>
                    <td><?php echo htmlspecialchars($ride['StartTime']); ?></td>
                </tr>

                <tr>
                    <th>End Time</th>
                    <td><?php echo htmlspecialchars($ride['EndTime']); ?></td>
                </tr>

            <?php } else { ?>

                <tr>
                    <td>Related ride not found.</td>
                </tr>

            <?php } ?>

        </table>

        <h2>Related Report</h2>

        <table>

            <?php if ($report) { ?>

                <tr>
                    <th>Report ID</th>
                    <td><?php echo htmlspecialchars($report['ReportID']); ?></td>
                </tr>

                <tr>
                    <th>Report Date</th>
                    <td><?php echo htmlspecialchars($report['ReportDate']); ?></td>
                </tr>

                <tr>
                    <th>Description</th>
                    <td><?php echo htmlspecialchars($report['ReportDescription']); ?></td>
                </tr>

            <?php } else { ?>

                <tr>
                    <td>No related report found.</td>
                </tr>

            <?php } ?>

        </table>

        <h2>Related Feedback</h2>

        <table>

            <tr>
                <th>Feedback ID</th>
                <th>Rating</th>
                <th>Comment</th>
                <th>Direction</th>
            </tr>

            <?php if ($feedbackResult && mysqli_num_rows($feedbackResult) > 0) { ?>

                <?php while ($feedback = mysqli_fetch_assoc($feedbackResult)) { ?>

                    <tr>
                        <td><?php echo htmlspecialchars($feedback['FeedbackID']); ?></td>
                        <td><?php echo htmlspecialchars($feedback['StarRating']); ?></td>
                        <td><?php echo htmlspecialchars($feedback['Comment']); ?></td>
                        <td><?php echo htmlspecialchars($feedback['FeedbackDirection']); ?></td>
                    </tr>

                <?php } ?>

            <?php } else { ?>

                <tr>
                    <td colspan="4">No related feedback found.</td>
                </tr>

            <?php } ?>

        </table>

        <div class="button-bar">

            <form method="POST">

                <select name="penalty_status">

                    <option value="Ongoing"
                        <?php if ($penalty['PenaltyStatus'] == "Ongoing") echo "selected"; ?>>
                        Ongoing
                    </option>

                    <option value="Active"
                        <?php if ($penalty['PenaltyStatus'] == "Active") echo "selected"; ?>>
                        Active
                    </option>

                    <option value="Completed"
                        <?php if ($penalty['PenaltyStatus'] == "Completed") echo "selected"; ?>>
                        Completed
                    </option>

                </select>

                <button type="submit" name="update_status" class="table-btn">
                    Update Status
                </button>

            </form>

            <form method="POST">

                <button
                    type="submit"
                    name="user_action"
                    value="Ban"
                    class="table-btn"
                >
                    Ban User
                </button>

            </form>

            <a href="ManagePenalty.php" class="table-btn">
                Back to Manage Penalties
            </a>

        </div>

    </div>

</body>

</html>