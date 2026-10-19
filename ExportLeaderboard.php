<?php
session_start();
include 'dbconn.php';

if (($_SESSION['role_id'] ?? 0) !== 4){ 
    header("Location: index.php"); exit(); 
}

header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="Leaderboard.csv"');

$output = fopen('php://output', 'w');
fputcsv($output, ['Rank', 'Volunteer ID', 'Username', 'Number of Rides Done']);

$result = $conn->query("SELECT v.VolunteerID, u.Username, v.NumberOfRidesDone FROM volunteerdriverprofile v JOIN user u ON v.UserID = u.UserID ORDER BY v.NumberOfRidesDone DESC LIMIT 50");

$rank = 1;
while ($row = $result->fetch_assoc()){
    fputcsv($output, [$rank++, $row['VolunteerID'], $row['Username'], $row['NumberOfRidesDone']]);
}
fclose($output);
?>