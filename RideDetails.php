<?php
session_start();
include 'dbconn.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 3) {
    header("Location: LoginPage.html");
    exit();
}

if (!isset($_GET['ride_id'])) {
    header("Location: MonitorRides.php");
    exit();
}

$rideID = intval($_GET['ride_id']);

$rideQuery = "SELECT r.RideID, r.DateOfRides, r.RideRequestID,
                     r.RideStatus, r.CarPlateNumber,
                     r.StartTime, r.EndTime,
                     p.DistanceBetweenKM
              FROM rides r
              JOIN riderequest rr
              ON r.RideRequestID = rr.RideRequestID
              JOIN passengerprofile p
              ON rr.PassengerID = p.PassengerID
              WHERE r.RideID = $rideID";

              

$rideResult = mysqli_query($conn, $rideQuery);
$ride = mysqli_fetch_assoc($rideResult);

$feedbackResult = null;
$estimatedCarbon = 0;
$emissionFactor = 0.192;

if ($ride) {
    $rideRequestID = $ride['RideRequestID'];

    $feedbackQuery = "SELECT FeedbackID, StarRating, Comment, FeedbackDirection
                      FROM feedback
                      WHERE RideID = $rideRequestID";

    $feedbackResult = mysqli_query($conn, $feedbackQuery);

if ($ride['DistanceBetweenKM'] !== null) {
    $estimatedCarbon = $ride['DistanceBetweenKM'] * $emissionFactor;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Ride Details</title>

    <link rel="stylesheet" href="styleA.css">
</head>

<body>

    <div class="center-wrapper">

        <h1>Ride Details</h1>

        <?php if ($ride) { ?>

            <h2>Ride Information</h2>

            <table>
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

                <tr>
                    <th>Distance</th>
                    <td><?php echo htmlspecialchars($ride['DistanceBetweenKM']); ?> km</td>
                </tr>

                <tr>
                    <th>Estimated Carbon Emissions</th>
                    <td><?php echo number_format($estimatedCarbon, 2); ?> kg CO₂</td>
                </tr>
            </table>

            <h2>Ride Feedback</h2>

            <table>
                <tr>
                    <th>Feedback ID</th>
                    <th>Rating</th>
                    <th>Comment</th>
                    <th>Feedback Direction</th>
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
                        <td colspan="4">No feedback found.</td>
                    </tr>

                <?php } ?>

            </table>

        <?php } else { ?>

            <p>Ride not found.</p>

        <?php } ?>

        <div class="button-bar">

            <a href="MonitorRides.php" class="table-btn">
                Back to Monitor Rides
            </a>

        </div>

    </div>

</body>

</html>