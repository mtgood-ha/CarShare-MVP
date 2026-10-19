<?php
include 'dbconn.php';
session_start();
date_default_timezone_set('Asia/Kuala_Lumpur');

$loggedInUserID = $_SESSION['user_id'] ?? 1;
$volunteerID = $_POST['driver_id'] ?? $_POST['volunteer_id'] ?? $_GET['driver_id'] ?? 1;

$passengerQuery = mysqli_query($conn, "SELECT PassengerID, PreferredPickUpLocation, PreferredDropOffLocation FROM passengerprofile WHERE UserID = '$loggedInUserID' LIMIT 1");
if ($passengerQuery && mysqli_num_rows($passengerQuery) > 0) {
    $passengerData = mysqli_fetch_assoc($passengerQuery);
    $passengerID = $passengerData['PassengerID'];
    $pickup = $passengerData['PreferredPickUpLocation'];
    $dropoff = $passengerData['PreferredDropOffLocation'];
} else {
    echo "Error: Passenger profile not found.";
    exit();
}

$checkOngoing = mysqli_query($conn, "SELECT RideRequestID FROM riderequest WHERE PassengerID = '$passengerID' AND RideRequestStatus = 'Ongoing' LIMIT 1");
if ($checkOngoing && mysqli_num_rows($checkOngoing) > 0) {
    header("Location: RideOngoingP.php");
    exit();
}

$carRes = mysqli_query($conn, "SELECT CarPlateNumber FROM car WHERE VolunteerID = '$volunteerID' LIMIT 1");
$carData = mysqli_fetch_assoc($carRes);
$carPlate = $carData['CarPlateNumber'] ?? 'UNKNOWN';

$currentDate = date('Y-m-d');
$requestTime = date('H:i:s');
$randomStartTime = sprintf('%02d:%02d:00', rand(8, 16), rand(0, 59));
$randomEndTime = sprintf('%02d:%02d:00', rand(17, 22), rand(0, 59));

$insertRequest = "INSERT INTO riderequest (PassengerID, VolunteerID, RideRequestStatus, RequestTime, PickUpLocation, DropOffLocation) 
                VALUES ('$passengerID', '$volunteerID', 'Ongoing', '$requestTime', '$pickup', '$dropoff')";
mysqli_query($conn, $insertRequest);
$rideRequestID = mysqli_insert_id($conn);

$insertRide = "INSERT INTO rides (RideRequestID, DateOfRides, StartTime, EndTime, CarPlateNumber, RideStatus) 
               VALUES ('$rideRequestID', '$currentDate', '$randomStartTime', '$randomEndTime', '$carPlate', 'ongoing')";

if (mysqli_query($conn, $insertRide)) {
    header("Location: RideOngoingP.php");
    exit();
} else {
    echo "Database Error: " . mysqli_error($conn);
}
?>