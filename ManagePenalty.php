<?php
session_start();
include 'dbconn.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 3) {
    header("Location: LoginPage.html");
    exit();
}

$search = isset($_GET['search']) ? trim($_GET['search']) : "";
$status = isset($_GET['status']) ? $_GET['status'] : "";

$sql = "SELECT PenaltyID, UserID, RideID, PenaltyDate, PenaltyReason, PenaltyStatus
        FROM penaltysystem
        WHERE 1=1";

if ($search != "") {
    $searchID = intval($search);

    $sql .= " AND UserID = $searchID";
}

if ($status != "") {
    $status = mysqli_real_escape_string($conn, $status);
    $sql .= " AND PenaltyStatus = '$status'";
}

$sql .= " ORDER BY PenaltyDate DESC";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Manage Penalties</title>

    <link rel="stylesheet" href="styleA.css">
</head>

<body>

    <div class="center-wrapper">

        <h1>Manage Penalties</h1>

        <form method="GET" action="ManagePenalty.php" class="search-bar">

            <input
                type="number"
                name="search"
                placeholder="Search User ID"
                value="<?php echo htmlspecialchars($search); ?>"
            >

            <select name="status">
                <option value="">All Status</option>

                <option value="Ongoing"
                    <?php if ($status == "Ongoing") echo "selected"; ?>>
                    Ongoing
                </option>

                <option value="Active"
                    <?php if ($status == "Active") echo "selected"; ?>>
                    Active
                </option>

                <option value="Completed"
                    <?php if ($status == "Completed") echo "selected"; ?>>
                    Completed
                </option>
            </select>

            <button type="submit" class="table-btn">
                Search
            </button>

        </form>

        <table>

            <tr>
                <th>Penalty ID</th>
                <th>User ID</th>
                <th>Ride ID</th>
                <th>Date</th>
                <th>Reason</th>
                <th>Status</th>
                <th>Action</th>
            </tr>

            <?php if ($result && mysqli_num_rows($result) > 0) { ?>

                <?php while ($row = mysqli_fetch_assoc($result)) { ?>

                    <tr>
                        <td><?php echo htmlspecialchars($row['PenaltyID']); ?></td>
                        <td><?php echo htmlspecialchars($row['UserID']); ?></td>
                        <td><?php echo htmlspecialchars($row['RideID']); ?></td>
                        <td><?php echo htmlspecialchars($row['PenaltyDate']); ?></td>
                        <td><?php echo htmlspecialchars($row['PenaltyReason']); ?></td>
                        <td><?php echo htmlspecialchars($row['PenaltyStatus']); ?></td>
                        <td>
                            <a
                                href="PenaltyDetail.php?penalty_id=<?php echo $row['PenaltyID']; ?>"
                                class="view-btn"
                            >
                                View
                            </a>
                        </td>
                    </tr>

                <?php } ?>

            <?php } else { ?>

                <tr>
                    <td colspan="7">No penalties found.</td>
                </tr>

            <?php } ?>

        </table>

        <div class="button-bar">

            <a href="IssuePenalty.php" class="table-btn">
                Issue Penalty
            </a>

            <a href="AdminDashboard.php" class="table-btn">
                Back to Dashboard
            </a>

        </div>

    </div>

</body>

</html>