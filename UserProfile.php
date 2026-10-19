<?php
session_start();
include 'dbconn.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 3) {
    header("Location: LoginPage.html");
    exit();
}

if (!isset($_GET['user_id'])) {
    header("Location: ManageUser.php");
    exit();
}

$userID = intval($_GET['user_id']);
$adminUserID = $_SESSION['user_id'];
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['account_action'])) {

    $action = $_POST['account_action'];

    if ($action == "Ban") {
        $newStatus = "Banned";
    } elseif ($action == "Unban") {
        $newStatus = "Active";
    } else {
        $newStatus = "";
    }

    if ($newStatus != "") {

        $updateQuery = "UPDATE user
                        SET AccountStatus = '$newStatus'
                        WHERE UserID = $userID";

        if (mysqli_query($conn, $updateQuery)) {

            if ($action == "Ban") {
                $message = "User account banned successfully.";
                $actionText = "Banned user account for User ID $userID";
            } else {
                $message = "User account unbanned successfully.";
                $actionText = "Unbanned user account for User ID $userID";
            }

            $actionText = mysqli_real_escape_string($conn, $actionText);

            $logQuery = "INSERT INTO adminactivitylog
                         (ActionType, TimeStamp, UserID, ActionDate)
                         VALUES
                         ('$actionText', CURTIME(), $adminUserID, CURDATE())";

            mysqli_query($conn, $logQuery);
        }
    }
}

$userQuery = "SELECT u.UserID, u.Name, u.Username, u.Email,
                     u.Gender, u.AccountStatus, r.RoleName
              FROM user u
              JOIN role r ON u.RoleID = r.RoleID
              WHERE u.UserID = $userID";

$userResult = mysqli_query($conn, $userQuery);
$user = mysqli_fetch_assoc($userResult);

$penaltyQuery = "SELECT PenaltyID, RideID, PenaltyDate,
                        PenaltyReason, PenaltyStatus
                 FROM penaltysystem
                 WHERE UserID = $userID
                 ORDER BY PenaltyDate DESC";

$penaltyResult = mysqli_query($conn, $penaltyQuery);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>User Profile</title>

    <link rel="stylesheet" href="styleA.css">
    <script src="Admin.js"></script>
</head>

<body>

    <div class="center-wrapper">

        <h1>User Profile</h1>

        <?php if ($message != "") { ?>
            <p><?php echo htmlspecialchars($message); ?></p>
        <?php } ?>

        <?php if ($user) { ?>

            <h2>User Details</h2>

            <table>

                <tr>
                    <th>User ID</th>
                    <td><?php echo htmlspecialchars($user['UserID']); ?></td>
                </tr>

                <tr>
                    <th>Name</th>
                    <td><?php echo htmlspecialchars($user['Name']); ?></td>
                </tr>

                <tr>
                    <th>Username</th>
                    <td><?php echo htmlspecialchars($user['Username']); ?></td>
                </tr>

                <tr>
                    <th>Email</th>
                    <td><?php echo htmlspecialchars($user['Email']); ?></td>
                </tr>

                <tr>
                    <th>Role</th>
                    <td><?php echo htmlspecialchars($user['RoleName']); ?></td>
                </tr>

                <tr>
                    <th>Gender</th>
                    <td><?php echo htmlspecialchars($user['Gender']); ?></td>
                </tr>

                <tr>
                    <th>Account Status</th>
                    <td><?php echo htmlspecialchars($user['AccountStatus']); ?></td>
                </tr>

            </table>

            <h2>Penalty History</h2>

            <table>

                <tr>
                    <th>Penalty ID</th>
                    <th>Ride ID</th>
                    <th>Date</th>
                    <th>Reason</th>
                    <th>Status</th>
                </tr>

                <?php if ($penaltyResult && mysqli_num_rows($penaltyResult) > 0) { ?>

                    <?php while ($penalty = mysqli_fetch_assoc($penaltyResult)) { ?>

                        <tr>
                            <td><?php echo htmlspecialchars($penalty['PenaltyID']); ?></td>
                            <td><?php echo htmlspecialchars($penalty['RideID']); ?></td>
                            <td><?php echo htmlspecialchars($penalty['PenaltyDate']); ?></td>
                            <td><?php echo htmlspecialchars($penalty['PenaltyReason']); ?></td>
                            <td><?php echo htmlspecialchars($penalty['PenaltyStatus']); ?></td>
                        </tr>

                    <?php } ?>

                <?php } else { ?>

                    <tr>
                        <td colspan="5">No penalty history found.</td>
                    </tr>

                <?php } ?>

            </table>

            <div class="button-bar">

            <?php if ($user['AccountStatus'] == "Banned") { ?>

                <form method="POST">
                    <button
                        type="submit"
                        name="account_action"
                        value="Unban"
                        class="table-btn"
                    >
                        Unban
                    </button>
                </form>

            <?php } else { ?>

                <form method="POST">
                    <button
                        type="submit"
                        name="account_action"
                        value="Ban"
                        class="table-btn"
                    >
                        Ban
                    </button>
                </form>

            <?php } ?>

            <a href="ManageUser.php" class="table-btn">
                Back to Manage Users
            </a>

        </div>

        <?php } else { ?>

            <p>User not found.</p>

            <a href="ManageUser.php" class="table-btn">
                Back to Manage Users
            </a>

        <?php } ?>

    </div>

</body>

</html>