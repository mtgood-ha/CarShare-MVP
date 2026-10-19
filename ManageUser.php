<?php
session_start();
include("dbconn.php");

if(!isset($_SESSION["user_id"]) || $_SESSION["role_id"] !=3){
    header("Location: LoginPage.html");
    exit();
}

$search = isset($_GET['search']) ? trim($_GET['search']) : "";
$role = isset($_GET['role']) ? $_GET['role'] : "";

$sql = "SELECT u.UserID, u.Name, u.Username, u.Email, u.AccountStatus, r.RoleName
        FROM user u
        JOIN role r ON u.RoleID = r.RoleID
        WHERE 1=1";

if ($search != "") {
    $searchID = intval($search);

    $sql .= " AND u.UserID = $searchID";
}

if ($role != "") {
    $role = intval($role);
    $sql .= " AND u.RoleID = $role";
}

$sql .= " ORDER BY u.UserID";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Manage User</title>
    <link rel="stylesheet" href="styleA.css">
</head>
<body>
    <div class="center-wrapper">
        <h1>Manage User</h1>

        <form method="GET" action="ManageUser.php" class="search-bar">

            <input
                type="number"
                name="search"
                placeholder="Search User ID"
                value="<?php echo htmlspecialchars($search); ?>"
            >

           <select name="role">
                <option value="">All Roles</option>
                <option value="2" <?php if ($role == "2") echo "selected"; ?>>
                    Passenger
                </option>
                <option value="1" <?php if ($role == "1") echo "selected"; ?>>
                    Volunteer Driver
                </option>
                <option value="3" <?php if ($role == "3") echo "selected"; ?>>
                    Admin
                </option>
                <option value="4" <?php if ($role == "4") echo "selected"; ?>>
                    Reward Manager
                </option>
            </select>

            <button type="submit" class="table-btn">Search</button>
        </form>

        <table>

            <tr>
                <th>User ID</th>
                <th>Name</th>
                <th>Username</th>
                <th>Role</th>
                <th>Email</th>
                <th>Status</th>
                <th>Action</th>
            </tr>

            <?php if ($result && mysqli_num_rows($result) > 0) { ?>

                <?php while ($row = mysqli_fetch_assoc($result)) { ?>

                    <tr>
                        <td><?php echo htmlspecialchars($row['UserID']); ?></td>
                        <td><?php echo htmlspecialchars($row['Name']); ?></td>
                        <td><?php echo htmlspecialchars($row['Username']); ?></td>
                        <td><?php echo htmlspecialchars($row['RoleName']); ?></td>
                        <td><?php echo htmlspecialchars($row['Email']); ?></td>
                        <td><?php echo htmlspecialchars($row['AccountStatus']); ?></td>
                        <td>
                            <a
                                href="UserProfile.php?user_id=<?php echo $row['UserID']; ?>"
                                class="view-btn"
                            >
                                View
                            </a>
                        </td>
                    </tr>

                <?php } ?>

            <?php } else { ?>

                <tr>
                    <td colspan="7">No users found.</td>
                </tr>

            <?php } ?>

        </table>

        <a href="AdminDashboard.php" class="table-btn">Back to Dashboard</a>
    </div>
</body>
</html>