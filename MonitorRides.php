<?php
session_start();
include 'dbconn.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 3) {
    header("Location: LoginPage.html");
    exit();
}

$status = isset($_GET['status']) ? $_GET['status'] : "";
$date = isset($_GET['date']) ? $_GET['date'] : "";

$sql = "SELECT RideID, DateOfRides, RideStatus, CarPlateNumber, StartTime, EndTime
        FROM rides
        WHERE 1=1";

if ($status != "") {
    $status = mysqli_real_escape_string($conn, $status);
    $sql .= " AND RideStatus = '$status'";
}

if ($date != "") {
    $date = mysqli_real_escape_string($conn, $date);
    $sql .= " AND DateOfRides = '$date'";
}

$sql .= " ORDER BY DateOfRides DESC";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Monitor Ride Status</title>

    <link rel="stylesheet" href="styleA.css">
</head>

<body>

    <div class="center-wrapper">

        <h1>Monitor Ride Status</h1>

        <form method="GET" action="MonitorRides.php" class="search-bar">

            <select name="status">
                <option value="">All Status</option>
                <option value="completed" <?php if ($status == "completed") echo "selected"; ?>>
                    Completed
                </option>
                <option value="accepted" <?php if ($status == "accepted") echo "selected"; ?>>
                    Accepted
                </option>
                <option value="Ongoing" <?php if ($status == "Ongoing") echo "selected"; ?>>
                    Ongoing
                </option>
                <option value="cancelled" <?php if ($status == "cancelled") echo "selected"; ?>>
                    Cancelled
                </option>
            </select>

            <input
                type="date"
                name="date"
                value="<?php echo htmlspecialchars($date); ?>"
            >

            <button type="submit" class="table-btn">
                Search
            </button>

        </form>

        <table>

            <tr>
                <th>Ride ID</th>
                <th>Date</th>
                <th>Status</th>
                <th>Vehicle</th>
                <th>Start Time</th>
                <th>End Time</th>
                <th>Action</th>
            </tr>

            <?php if ($result && mysqli_num_rows($result) > 0) { ?>

                <?php while ($row = mysqli_fetch_assoc($result)) { ?>

                    <tr>
                        <td><?php echo htmlspecialchars($row['RideID']); ?></td>
                        <td><?php echo htmlspecialchars($row['DateOfRides']); ?></td>
                        <td><?php echo htmlspecialchars($row['RideStatus']); ?></td>
                        <td><?php echo htmlspecialchars($row['CarPlateNumber']); ?></td>
                        <td><?php echo htmlspecialchars($row['StartTime']); ?></td>
                        <td><?php echo htmlspecialchars($row['EndTime']); ?></td>
                        <td>
                            <a
                                href="RideDetails.php?ride_id=<?php echo $row['RideID']; ?>"
                                class="view-btn"
                            >
                                View
                            </a>
                        </td>
                    </tr>

                <?php } ?>

            <?php } else { ?>

                <tr>
                    <td colspan="7">No rides found.</td>
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