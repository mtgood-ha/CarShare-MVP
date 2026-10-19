<?php
include "dbconn.php";
session_start();

if ($conn->connect_error){
    die("Connection failed: " . $conn->connect_error);
}

$search_term = "";
$results = null;

if (isset($_GET['query'])){
    $search_term = trim($_GET['query']);

    if (!empty($search_term)){
        $sql = "SELECT UserID, Username, Name, Email, RoleID 
                FROM user 
                WHERE RoleID = 1 AND (Username LIKE ? OR Name LIKE ? OR Email LIKE ?)";
        
        $stmt = $conn->prepare($sql);
        $like_term = "%" . $search_term . "%";
        $stmt->bind_param("sss", $like_term, $like_term, $like_term);
        $stmt->execute();
        $results = $stmt->get_result();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Search Volunteer Driver</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <link rel="stylesheet" href="styleR.css">
</head>
<body>

    <h2>Search Volunteer Drivers</h2>

    <form action="SearchUser.php" method="GET">
        <label>Search Volunteer:</label>
        <input type="text" name="query" value="<?php echo htmlspecialchars($search_term); ?>" required>
        <div style="text-align: center;">
            <button type="submit">Search</button>
        </div>
    </form>

    <br>

    <?php if (isset($_GET['query'])): ?>
        <h3>Search Results for: "<?php echo htmlspecialchars($search_term); ?>"</h3>

        <?php if ($results && $results->num_rows > 0): ?>
            <table border="1" cellpadding="8" cellspacing="0">
                <thead>
                    <tr>
                        <th>User ID</th>
                        <th>Name</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $results->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['UserID']; ?></td>
                            <td><?php echo htmlspecialchars($row['Name']); ?></td>
                            <td><?php echo htmlspecialchars($row['Username']); ?></td>
                            <td><?php echo htmlspecialchars($row['Email']); ?></td>
                            <td>Volunteer Driver</td>
                            <td>
                                <a href="viewdetails.php?email=<?php echo urlencode($row['Email']); ?>">View Details</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No volunteer drivers found matching your search term.</p>
        <?php endif; ?>
    <?php endif; ?>

    <p><a href="RewardManagerHomepage.html"><button type="button" class="backBtn">Back to Homepage</button></a></p>

</body>
</html>

<?php
if (isset($stmt)){
    $stmt->close();
}
$conn->close();
?>