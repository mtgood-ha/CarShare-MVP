<?php
    include 'dbconn.php';
    session_start();

    if (!isset($_SESSION['user_id'])) {
        header("Location: LoginPage.html"); 
        exit();
    }

    $userID = $_SESSION['user_id'];    
    $valueUsername = "";
    $valueEmail = "";
    $valueGender = "";
    $valuePickup = "";
    $valueDropoff = "";
    $valueDistance = "";
    $starRating = ""; 
    $currentPoints = ""; 
    $message = "";

   if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $newUsername = mysqli_real_escape_string($conn, $_POST['Username']);
        $newEmail = mysqli_real_escape_string($conn, $_POST['email']);
        $newGender = mysqli_real_escape_string($conn, $_POST['Gender']);
        $newPickup = mysqli_real_escape_string($conn, $_POST['PreferredPickUpLocation']);
        $newDropoff = mysqli_real_escape_string($conn, $_POST['PreferredDropOffLocation']);
        $newDistance = mysqli_real_escape_string($conn, $_POST['DistanceBetweenKM']);

        if (!is_numeric($newDistance)) {
            $message = "Distance Between (km) must be a number!!";
        } else {
            $updateUsersQuery = "UPDATE user 
                                 SET Username = '$newUsername', 
                                     Email = '$newEmail', 
                                     Gender = '$newGender' 
                                 WHERE userID = '$userID'";
            $usersUpdated = mysqli_query($conn, $updateUsersQuery);

            $updateProfileQuery = "UPDATE volunteerdriverprofile 
                                   SET PreferredPickUpLocation = '$newPickup', 
                                       PreferredDropOffLocation = '$newDropoff', 
                                       DistanceBetweenKM = '$newDistance' 
                                   WHERE userID = '$userID'";
            $profileUpdated = mysqli_query($conn, $updateProfileQuery);
            
            if ($usersUpdated && $profileUpdated) {
                $message = "Profile updated successfully!";
            } else {
                $message = "Error occurred! " . mysqli_error($conn); 
            }
        }
    }
    
    $query = "SELECT u.Username, u.Email, u.Gender, v.CurrentPoints, 
                     v.StarRating, v.PreferredPickUpLocation, v.PreferredDropOffLocation, v.DistanceBetweenKM
              FROM user u
              JOIN volunteerdriverprofile v ON u.userID = v.userID 
              WHERE u.userID = '$userID'";
              
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $userData = mysqli_fetch_assoc($result);
        $valueUsername = $userData['Username'];
        $valueEmail = $userData['Email'];
        $valueGender = $userData['Gender'];
        $currentPoints = $userData['CurrentPoints'];
        $starRating = $userData['StarRating'];
        $valuePickup = $userData['PreferredPickUpLocation'];
        $valueDropoff = $userData['PreferredDropOffLocation'];
        $valueDistance = $userData['DistanceBetweenKM'];
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile</title>
    <link rel="stylesheet" href="styleV.css">
</head>
<body>

<div class="center-wrapper">
    <h2>My Profile</h2>
    
    <form action="" method="POST" id="accountDetails" style="width: 100%;">
        <div class="textBorder centerText BORDERcontainerPERSONALDETAILS">

            <?php if ($message): ?>
                <p style="color: green; text-align: center; font-weight: bold; width: 100%;"><?php echo $message; ?></p>
            <?php endif; ?>

            <img style="border: 1px solid black;" src="profilepic1.jpg" width="150" height="150" alt="Profile Picture">

            <div class="form-row">
                <label for="Username">Username:</label>
                <input type="text" id="Username" name="Username" value="<?php echo htmlspecialchars($valueUsername); ?>" style="max-width: 500px;" required>
            </div>

            <div class="form-row">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($valueEmail); ?>" style="max-width: 500px;" required>
            </div>

            <div class="form-row">
                <label for="Gender">Gender:</label>
                <div style="display: flex; align-items: center; width: 100%; max-width: 500px; gap: 20px; flex-wrap: nowrap;">
                    
                    <select id="Gender" name="Gender" style="width: 110px; flex-shrink: 0;" required>
                        <option value="" disabled <?php if(empty($valueGender)) echo 'selected'; ?> hidden>Select</option>
                        <option value="Female" <?php if($valueGender == 'Female') echo 'selected'; ?>>Female</option>
                        <option value="Male" <?php if($valueGender == 'Male') echo 'selected'; ?>>Male</option>
                    </select>
                    
                    <div style="display: flex; align-items: center; white-space: nowrap;">
                        <label style="width: auto; margin-right: 10px; margin-bottom: 0; text-align: right;">Rating:</label>
                        <input type="text" value="<?php echo htmlspecialchars($starRating ?? ''); ?>" readonly style="width: 100px; flex-shrink: 0; background-color: #e3edf3; font-weight: bold; cursor: not-allowed;">
                    </div>

                </div>
            </div>

            <div class="form-row">
                <label for="PreferredPickUpLocation">Preferred Pick-Up Location:</label>
                <input type="text" id="PreferredPickUpLocation" name="PreferredPickUpLocation" value="<?php echo htmlspecialchars($valuePickup); ?>" style="max-width: 500px;">
            </div>

            <div class="form-row">
                <label for="PreferredDropOffLocation">Preferred Drop-Off Location:</label>
                <input type="text" id="PreferredDropOffLocation" name="PreferredDropOffLocation" value="<?php echo htmlspecialchars($valueDropoff); ?>" style="max-width: 500px;">
            </div>

            <div class="form-row">
                <label for="DistanceBetweenKM">Distance Between (km):</label>
                <input type="number" id="DistanceBetweenKM" name="DistanceBetweenKM" value="<?php echo htmlspecialchars($valueDistance); ?>" style="max-width: 500px;" required>
            </div>
        
            <div class="form-row">
                <label>Number of Points Accumulated:</label>
                <input type="text" value="<?php echo htmlspecialchars($currentPoints ?? ''); ?>" readonly style="max-width: 500px; background-color: #e3edf3; cursor: not-allowed; font-weight: bold;">
            </div>
            
        </div>
        
        <div class="buttonDisplay" style="margin-top: 20px; gap: 30px;">
            <button type="button" class="baseBtn" onclick="window.location.href='ManageAccount.php';">Back</button>
            <button type="submit" class="baseBtn">Save Changes</button>
        </div>
        
    </form>
</div>
</body>
</html>