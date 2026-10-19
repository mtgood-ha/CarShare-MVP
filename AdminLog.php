<?php
session_start();
include 'dbconn.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 3) {
    header("Location: LoginPage.html");
    exit();
}

$search = isset($_GET['search']) ? trim($_GET['search']) : "";

$sql = "SELECT LogID, UserID, ActionType, ActionDate, TimeStamp
        FROM adminactivitylog
        WHERE 1=1";

if ($search != "") {
    $safeSearch = mysqli_real_escape_string($conn, $search);

    $sql .= " AND ActionType LIKE '%$safeSearch%'";
}

$sql .= " ORDER BY ActionDate DESC, TimeStamp DESC";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Activity Log</title>
    <link rel="stylesheet" href="styleA.css">
</head>

<body>

    <div class="center-wrapper">

        <h1>Admin Activity Log</h1>

        <form method="GET" action="AdminLog.php" class="search-bar">

            <input
                type="text"
                name="search"
                placeholder="Search Action"
                value="<?php echo htmlspecialchars($search); ?>"
            >

            <button type="submit" class="table-btn">
                Search
            </button>

        </form>

        <table>

            <tr>
                <th>Log ID</th>
                <th class="admintxt">Admin<br>User ID</th>
                <th>Action</th>
                <th>Date</th>
                <th>Time</th>
            </tr>

            <?php if ($result && mysqli_num_rows($result) > 0) { ?>

                <?php while ($row = mysqli_fetch_assoc($result)) { ?>

                    <tr>
                        <td><?php echo htmlspecialchars($row['LogID']); ?></td>

                        <td class="admintxt">
                            <?php echo htmlspecialchars($row['UserID']); ?>
                        </td>

                        <td><?php echo htmlspecialchars($row['ActionType']); ?></td>
                        <td><?php echo htmlspecialchars($row['ActionDate']); ?></td>
                        <td><?php echo htmlspecialchars($row['TimeStamp']); ?></td>
                    </tr>

                <?php } ?>

            <?php } else { ?>

                <tr>
                    <td colspan="5">No activity logs found.</td>
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