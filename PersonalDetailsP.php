<?php
    include 'dbconn.php';
    session_start();

    $userID = $_SESSION['user_id'] ?? null;
    
    $valueUsername = "";
    $valueEmail = "";
    $valueGender = "";
    $valuePickup = "";
    $valueDropoff = "";
    $valueDistance = "";
    $starRating = ""; 
    $successMsg = "";

    if ($userID) {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $newUsername = mysqli_real_escape_string($conn, $_POST['Username']);
            $newEmail = mysqli_real_escape_string($conn, $_POST['email']);
            $newGender = mysqli_real_escape_string($conn, $_POST['Gender']);
            $newPickup = mysqli_real_escape_string($conn, $_POST['PreferredPickUpLocation']);
            $newDropoff = mysqli_real_escape_string($conn, $_POST['PreferredDropOffLocation']);
            $newDistance = trim($_POST['DistanceBetweenKM']);
            if ($newDistance === "" || intval($newDistance) <= 0) {
                $successMsg = "Error: Distance cannot be empty or 0.";
            } else {
                $newDistance = mysqli_real_escape_string($conn, $newDistance);
                
                $updateUsersQuery = "UPDATE user
                                     SET Username = '$newUsername',
                                         Email = '$newEmail',
                                         Gender = '$newGender'
                                     WHERE UserID = '$userID'";
                $usersUpdated = mysqli_query($conn, $updateUsersQuery);

                $updateProfileQuery = "UPDATE passengerprofile 
                                       SET PreferredPickUpLocation = '$newPickup', 
                                           PreferredDropOffLocation = '$newDropoff',
                                           DistanceBetweenKM = '$newDistance'
                                       WHERE UserID = '$userID'";
                $profileUpdated = mysqli_query($conn, $updateProfileQuery);
                
                if ($usersUpdated && $profileUpdated) {
                    $successMsg = "Profile updated successfully!";
                } else {
                    $successMsg = "Error occurred: " . mysqli_error($conn); 
                }
            }
        }

        $query = "SELECT u.Username, u.Email, u.Gender, p.StarRating, p.PreferredPickUpLocation, p.PreferredDropOffLocation, p.DistanceBetweenKM 
                  FROM user u
                  JOIN passengerprofile p ON u.UserID = p.UserID 
                  WHERE u.UserID = '$userID'";
                  
        $result = mysqli_query($conn, $query);

        if ($result && mysqli_num_rows($result) > 0) {
            $userData = mysqli_fetch_assoc($result);
            $valueUsername = $userData['Username'];
            $valueEmail = $userData['Email'];
            $valueGender = $userData['Gender'];
            $starRating = $userData['StarRating'];
            $valuePickup = $userData['PreferredPickUpLocation'];
            $valueDropoff = $userData['PreferredDropOffLocation'];
            $valueDistance = $userData['DistanceBetweenKM'];
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile</title>
    <link rel="stylesheet" href="styleP.css?v=5">
</head>
<body>

<div class="center-wrapper">
    <h2>My Profile</h2>
    
    <form action="" method="POST" style="width: 100%;">
        <div class="textBorder centerText BORDERcontainerPERSONALDETAILS">

            <?php if ($successMsg): ?>
                <p style="color: green; text-align: center; font-weight: bold; width: 100%;"><?php echo $successMsg; ?></p>
            <?php endif; ?>

            <img src="profilepic4.jpg" width="150" height="150" alt="Profile Picture">

            <div class="form-row">
                <label for="Username">Username:</label>
                <input type="text" id="Username" name="Username" value="<?php echo htmlspecialchars($valueUsername); ?>" required>
            </div>

            <div class="form-row">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($valueEmail); ?>" required>
            </div>

            <div class="form-row">
                <label for="Gender">Gender:</label>
                <div class="gender-rating-wrapper">
                    <select id="Gender" name="Gender" required>
                        <option value="" disabled <?php if(empty($valueGender)) echo 'selected'; ?> hidden>Select</option>
                        <option value="Female" <?php if($valueGender == 'Female') echo 'selected'; ?>>Female</option>
                        <option value="Male" <?php if($valueGender == 'Male') echo 'selected'; ?>>Male</option>
                    </select>
                    
                    <span class="rating-label">Rating:</span>
                    <input type="text" value="<?php echo htmlspecialchars($starRating ?? ''); ?>" readonly class="rating-input">
                </div>
            </div>

            <div class="form-row">
                <label for="PreferredPickUpLocation">Preferred Pick-Up Location:</label>
                <input type="text" id="PreferredPickUpLocation" name="PreferredPickUpLocation" value="<?php echo htmlspecialchars($valuePickup); ?>">
            </div>

            <div class="form-row">
                <label for="PreferredDropOffLocation">Preferred Drop-Off Location:</label>
                <input type="text" id="PreferredDropOffLocation" name="PreferredDropOffLocation" value="<?php echo htmlspecialchars($valueDropoff); ?>">
            </div>

            <div class="form-row">
                <label for="DistanceBetweenKM">Distance (KM):</label>
                <input type="number" id="DistanceBetweenKM" name="DistanceBetweenKM" step="1" min="1" value="<?php echo htmlspecialchars($valueDistance); ?>" required style="width: 100%; padding: 8px; border: 1px solid #080808; border-radius: 6px; box-sizing: border-box;">
            </div>
            
        </div>
        
        <div class="buttonDisplay">
            <button type="button" class="baseBtn" onclick="window.location.href='MainPageP.php';">Back</button>
            <button type="submit" class="baseBtn">Save Changes</button>
        </div>
    </form>
</div>

</body>
</html>