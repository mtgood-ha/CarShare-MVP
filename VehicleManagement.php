<?php
    session_start();
    include 'dbconn.php';
    $userID = $_SESSION["user_id"];
    
    $valueCarPlateNumber = "";
    $valueVehicleModel = "";
    $valueVehicleColour = "";
    $valueVehicleCapacity = "";
    $valueVehicleType = "";
    $message = "";
    $getVolunteerDetailsQuery = "SELECT volunteerID FROM volunteerdriverprofile WHERE userID = '$userID'";
    $getVolunteerDetails = mysqli_query($conn, $getVolunteerDetailsQuery);
    
    if (mysqli_num_rows($getVolunteerDetails) > 0) {
        $vID = mysqli_fetch_assoc($getVolunteerDetails);
        $volunteerID = $vID['volunteerID'];
    }
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['saveBtn'])) {
            $newPlate = mysqli_real_escape_string($conn, $_POST['CarPlateNumber']);
            $newModel = mysqli_real_escape_string($conn, $_POST['VehicleModel']);
            $newColour = mysqli_real_escape_string($conn, $_POST['VehicleColour']);
            $newCapacity = mysqli_real_escape_string($conn, $_POST['VehicleCapacity']);
            $newType = mysqli_real_escape_string($conn, $_POST['VehicleType']);

        if (!is_numeric($newCapacity)) {
            $message = "<span style='color: red; font-weight: bold;'>Vehicle Capacity must be a number!</span>";
        } elseif ($volunteerID) {
            $checkCarQuery = "SELECT CarPlateNumber FROM car WHERE volunteerID = '$volunteerID'";
            $checkCarResult = mysqli_query($conn, $checkCarQuery);
            if (mysqli_num_rows($checkCarResult) > 0) {
                $updateQuery = "UPDATE car 
                                SET CarPlateNumber = '$newPlate',VehicleModel = '$newModel', VehicleColour = '$newColour', VehicleCapacity = '$newCapacity',VehicleType = '$newType'
                                WHERE volunteerID = '$volunteerID'";
                
                if (mysqli_query($conn, $updateQuery)) {
                    $message = "<span style='color: green; font-weight: bold;'>Vehicle details updated successfully!</span>";
                } else {
                    $message = "<span style='color: red; font-weight: bold;'>Updating failed. " . mysqli_error($conn) . "</span>";
                }
            } else {
                $insertQuery = "INSERT INTO car (CarPlateNumber, VehicleModel, VehicleColour, VehicleCapacity, VehicleType, volunteerID) 
                                VALUES ('$newPlate', '$newModel', '$newColour', '$newCapacity', '$newType', '$volunteerID')";
                
                if (mysqli_query($conn, $insertQuery)) {
                    $message = "<span style='color: green; font-weight: bold;'>New vehicle added successfully!</span>";
                } else {
                    $message = "<span style='color: red; font-weight: bold;'>Adding failed. " . mysqli_error($conn) . "</span>";
                }
            }


        }

    }

    $query = "SELECT c.CarPlateNumber, c.VehicleModel, c.VehicleColour, c.VehicleCapacity, c.VehicleType 
              FROM car c
              JOIN volunteerdriverprofile v ON c.volunteerID = v.volunteerID
              WHERE v.userID = '$userID'";
    $result = mysqli_query($conn, $query);
    
    if (mysqli_num_rows($result) > 0) {
        $volunteerDetailsArray = mysqli_fetch_assoc($result);
        $valueCarPlateNumber = $volunteerDetailsArray['CarPlateNumber'];
        $valueVehicleModel = $volunteerDetailsArray['VehicleModel'];
        $valueVehicleColour = $volunteerDetailsArray['VehicleColour'];
        $valueVehicleCapacity = $volunteerDetailsArray['VehicleCapacity'];
        $valueVehicleType = $volunteerDetailsArray['VehicleType'];
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vehicle Management</title>
    <link rel="stylesheet" href="styleV.css?v=5">
</head>
<body>

    <div class="center-wrapper" style="max-width: 800px;">
        <h2>Vehicle Management</h2>
        
        <form action="" method="POST" id="vehicleDetails" style="width: 100%;">
            
            <div class="textBorder centerText" style="flex-direction: column;">
                
                <?php if ($message): ?>
                    <p style="text-align: center; margin-top: 15px;"><?php echo $message; ?></p>
                <?php endif; ?>

                <div style="display: flex; width: 100%; align-items: center; justify-content: space-between;">
                    <div class="vehicle-info-box">
                        <div class="form-row">
                            <label for="CarPlateNumber">Car Plate Number:</label>
                            <input type="text" id="CarPlateNumber" name="CarPlateNumber" value="<?php echo htmlspecialchars($valueCarPlateNumber); ?>" required>
                        </div>

                        <div class="form-row">
                            <label for="VehicleModel">Vehicle Model:</label>
                            <input type="text" id="VehicleModel" name="VehicleModel" value="<?php echo htmlspecialchars($valueVehicleModel); ?>" required>
                        </div>

                        <div class="form-row">
                            <label for="VehicleColour">Car Colour:</label>
                            <input type="text" id="VehicleColour" name="VehicleColour" value="<?php echo htmlspecialchars($valueVehicleColour); ?>" required>
                        </div>

                        <div class="form-row">
                            <label for="VehicleCapacity">Vehicle Capacity:</label>
                            <input type="text" id="VehicleCapacity" name="VehicleCapacity" value="<?php echo htmlspecialchars($valueVehicleCapacity); ?>" required>
                        </div>

                        <div class="form-row">
                            <label for="VehicleType">Vehicle Type:</label>
                            <input type="text" id="VehicleType" name="VehicleType" value="<?php echo htmlspecialchars($valueVehicleType); ?>" required>
                        </div>
                    </div>
                </div>

            </div>
            
            <div class="buttonDisplay bottomButton" style="margin-top: 20px;">
                <button type="button" class="baseBtn" onclick="window.location.href='ManageAccount.php';">Back</button>
                <input type="submit" name="saveBtn" value="Save Changes" class="baseBtn">
            </div>

        </form>
    </div>
</body>
</html>