<?php
session_start();
include 'dbconn.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 3) {
    header("Location: LoginPage.html");
    exit();
}

$message = "";
$userID = "";
$rideID = "";
$reason = "";
$adminUserID = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['issue_penalty'])) {

    $userID = intval($_POST['user_id']);
    $rideID = intval($_POST['ride_id']);
    $reason = trim($_POST['penalty_reason']);

    if ($userID <= 0 || $rideID <= 0 || $reason == "") {

        $message = "Please complete all fields.";

    } else {

        $userCheck = mysqli_query(
            $conn,
            "SELECT UserID FROM user WHERE UserID = $userID"
        );

        $rideCheck = mysqli_query(
            $conn,
            "SELECT RideID FROM rides WHERE RideID = $rideID"
        );

        if (!$userCheck || mysqli_num_rows($userCheck) == 0) {

            $message = "User ID does not exist.";

        } elseif (!$rideCheck || mysqli_num_rows($rideCheck) == 0) {

            $message = "Ride ID does not exist.";

        } else {

            $safeReason = mysqli_real_escape_string($conn, $reason);

            $insertQuery = "INSERT INTO penaltysystem
                            (RideID, UserID, PenaltyDate, PenaltyReason, PenaltyStatus)
                            VALUES
                            ($rideID, $userID, CURDATE(), '$safeReason', 'Ongoing')";

            if (mysqli_query($conn, $insertQuery)) {

                $newPenaltyID = mysqli_insert_id($conn);

                $actionText = "Issued Penalty ID $newPenaltyID to User ID $userID";
                $actionText = mysqli_real_escape_string($conn, $actionText);

                $logQuery = "INSERT INTO adminactivitylog
                             (ActionType, TimeStamp, UserID, ActionDate)
                             VALUES
                             ('$actionText', CURTIME(), $adminUserID, CURDATE())";

                mysqli_query($conn, $logQuery);

                $message = "Penalty issued successfully.";

                $userID = "";
                $rideID = "";
                $reason = "";

            } else {

                $message = "Unable to issue penalty.";

            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Issue Penalty</title>

    <link rel="stylesheet" href="styleA.css">
</head>

<body>

    <div class="center-wrapper">

        <h1>Issue Penalty</h1>

        <?php if ($message != "") { ?>
            <p><?php echo htmlspecialchars($message); ?></p>
        <?php } ?>

        <form method="POST" action="IssuePenalty.php">

            <table>

                <tr>
                    <th>User ID</th>
                    <td>
                        <input
                            type="number"
                            name="user_id"
                            value="<?php echo htmlspecialchars($userID); ?>"
                            required
                        >
                    </td>
                </tr>

                <tr>
                    <th>Ride ID</th>
                    <td>
                        <input
                            type="number"
                            name="ride_id"
                            value="<?php echo htmlspecialchars($rideID); ?>"
                            required
                        >
                    </td>
                </tr>

                <tr>
                    <th>Penalty Reason</th>
                    <td>
                        <input
                            type="text"
                            name="penalty_reason"
                            value="<?php echo htmlspecialchars($reason); ?>"
                            required
                        >
                    </td>
                </tr>

                <tr>
                    <th>Penalty Status</th>
                    <td>Ongoing</td>
                </tr>

                <tr>
                    <th>Penalty Date</th>
                    <td>Today's Date</td>
                </tr>

            </table>

            <div class="button-bar">

                <button
                    type="submit"
                    name="issue_penalty"
                    class="table-btn"
                >
                    Issue Penalty
                </button>

                <a href="ManagePenalty.php" class="table-btn">
                    Back to Manage Penalties
                </a>

            </div>

        </form>

    </div>

</body>

</html>