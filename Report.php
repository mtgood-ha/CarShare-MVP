<?php
session_start();
include 'dbconn.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 3) {
    header("Location: LoginPage.html");
    exit();
}   

$startDate = isset($_GET['start_date']) ? $_GET['start_date'] : "";
$endDate = isset($_GET['end_date']) ? $_GET['end_date'] : "";

$completedRides = 0;
$activeUsers = 0;
$totalDistance = 0;
$estimatedCarbon = 0;
$emissionFactor = 0.192;
$dateError = "";
$rides = [];
$activeUserIDs = [];

if ($startDate != "" && $endDate != "" && $startDate > $endDate) {
    $dateError = "Start date cannot be later than end date.";
}

if ($dateError == "") {

$sql = "SELECT r.RideID, r.DateOfRides, r.RideStatus,
               r.CarPlateNumber, r.StartTime, r.EndTime,
               CAST(p.DistanceBetweenKM AS DECIMAL(10,2)) AS RideDistanceKM,
               p.UserID AS PassengerUserID,
               v.UserID AS VolunteerUserID
        FROM rides r
        JOIN riderequest rr ON r.RideRequestID = rr.RideRequestID
        JOIN passengerprofile p ON rr.PassengerID = p.PassengerID
        JOIN volunteerdriverprofile v ON rr.VolunteerID = v.VolunteerID
        WHERE 1=1";

    if ($startDate != "") {
        $safeStartDate = mysqli_real_escape_string($conn, $startDate);
        $sql .= " AND r.DateOfRides >= '$safeStartDate'";
    }

    if ($endDate != "") {
        $safeEndDate = mysqli_real_escape_string($conn, $endDate);
        $sql .= " AND r.DateOfRides <= '$safeEndDate'";
    }

    $sql .= " ORDER BY r.DateOfRides DESC";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {

            $rides[] = $row;

            if (strtolower($row['RideStatus']) == "completed") {
                $completedRides++;
            }

            if ($row['RideDistanceKM'] !== null) {
                $totalDistance += $row['RideDistanceKM'];
            }

            $activeUserIDs[$row['PassengerUserID']] = true;
            $activeUserIDs[$row['VolunteerUserID']] = true;
        }
    }

    $activeUsers = count($activeUserIDs);
    $estimatedCarbon = $totalDistance * $emissionFactor;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Reports and Carbon Emissions</title>

    <link rel="stylesheet" href="styleA.css">
    <script src="Admin.js"></script>
</head>

<body>

    <div class="center-wrapper">

        <h1>Reports and Carbon Emissions</h1>

        <form method="GET" action="Report.php" class="search-bar">

            <input
                type="date"
                name="start_date"
                value="<?php echo htmlspecialchars($startDate); ?>"
            >

            <input
                type="date"
                name="end_date"
                value="<?php echo htmlspecialchars($endDate); ?>"
            >

            <button type="submit" class="table-btn">
                Generate Report
            </button>

        </form>

        <?php if ($dateError != "") { ?>
            <p><?php echo htmlspecialchars($dateError); ?></p>
        <?php } ?>

        <div class="stats-wrapper">

            <div class="stat-box">
                <h3>Completed Rides</h3>
                <p class="stat-number"><?php echo $completedRides; ?></p>
            </div>

            <div class="stat-box">
                <h3>Active Users</h3>
                <p class="stat-number"><?php echo $activeUsers; ?></p>
            </div>

            <div class="stat-box">
                <h3>Total Distance</h3>
                <p class="stat-number"><?php echo number_format($totalDistance, 2); ?> km</p>
            </div>

            <div class="stat-box">
                <h3>Estimated Carbon Emissions</h3>
                <p class="stat-number"><?php echo number_format($estimatedCarbon, 2); ?> kg</p>
            </div>

        </div>

        <table>

            <tr>
                <th>Ride ID</th>
                <th>Date</th>
                <th>Status</th>
                <th>Vehicle</th>
                <th>Start Time</th>
                <th>End Time</th>
            </tr>

            <?php if (count($rides) > 0) { ?>

                <?php foreach ($rides as $ride) { ?>

                    <tr>
                        <td><?php echo htmlspecialchars($ride['RideID']); ?></td>
                        <td><?php echo htmlspecialchars($ride['DateOfRides']); ?></td>
                        <td><?php echo htmlspecialchars(ucfirst($ride['RideStatus'])); ?></td>
                        <td><?php echo htmlspecialchars($ride['CarPlateNumber']); ?></td>
                        <td><?php echo htmlspecialchars($ride['StartTime']); ?></td>
                        <td><?php echo htmlspecialchars($ride['EndTime']); ?></td>
                    </tr>

                <?php } ?>

            <?php } else { ?>

                <tr>
                    <td colspan="6">No rides found.</td>
                </tr>

            <?php } ?>

        </table>

        <div class="button-bar">

            <a href="AdminDashboard.php" class="table-btn">
                Back to Dashboard
            </a>

        </div>

    </div>

</body>

</html>