<?php
session_start();
include 'dbconn.php';

$user_role = isset($_SESSION['role_id']) ? (int)$_SESSION['role_id'] : 0;
if ($user_role !== 4){
    header("Location: index.php");
    exit();
}

$error_msg = "";

if (isset($_POST['analyze_data'])){
    if (isset($_FILES['csv_file']) && $_FILES['csv_file']['error'] === UPLOAD_ERR_OK){
        $file_tmp_path = $_FILES['csv_file']['tmp_name'];
        $file_extension = strtolower(pathinfo($_FILES['csv_file']['name'], PATHINFO_EXTENSION));

        if ($file_extension === 'csv'){
            $rewards_map = [];
            if ($conn){
                $reward_query = $conn->query("SELECT RewardID, RewardName, RewardCost FROM reward");
                if ($reward_query) {
                    while ($r = $reward_query->fetch_assoc()){
                        $rewards_map[(int)$r['RewardID']] = [
                            'name' => trim($r['RewardName']),
                            'cost' => (int)$r['RewardCost']
                        ];
                    }
                }
            }

            $parsed_data = [];

            if (($handle = fopen($file_tmp_path, "r")) !== FALSE){
                fgetcsv($handle, 1000, ",", '"', "\\");

                while (($row = fgetcsv($handle, 1000, ",", '"', "\\")) !== FALSE){
                    if (empty($row) || (count($row) === 1 && empty($row[0]))){
                        continue;
                    }

                    $row = array_map(function($field){
                        $field = preg_replace('/[\x00-\x1F\x7F\xEF\xBB\xBF]/', '', $field);
                        return trim($field, " \t\n\r\0\x0B\"'");
                    }, $row);

                    if (count($row) >= 4 && is_numeric($row[1])){
                        $rvd_id       = intval($row[0]);
                        $reward_id    = intval($row[1]);
                        $volunteer_id = intval($row[2]);
                        $date_claimed = $row[3];

                        if (isset($rewards_map[$reward_id])){
                            $reward_name = $rewards_map[$reward_id]['name'];
                            $points_cost = $rewards_map[$reward_id]['cost'];
                        }else{
                            $reward_name = "Reward #" . $reward_id;
                            $points_cost = 50;
                        }

                        $parsed_data[] = [
                            'transaction_id'=> $rvd_id,
                            'reward_id'=> $reward_id,
                            'volunteer_id'=> $volunteer_id,
                            'points'=> -abs($points_cost),
                            'description'=> "Redeemed: " . $reward_name,
                            'date'=> $date_claimed
                        ];
                    }
                }
                fclose($handle);

                if (!empty($parsed_data)){
                    $_SESSION['analytics_data'] = $parsed_data;
                    header("Location: AnalyticsDashboard.php");
                    exit();
                }else{
                    $error_msg = "The CSV file appears to be empty or improperly formatted.";
                }
            }else{
                $error_msg = "Unable to read the uploaded CSV file.";
            }
        }else{
            $error_msg = "Please upload a valid .csv file.";
        }
    }else{
        $error_msg = "Please select a CSV file before clicking Analyze.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Import Reward Analytics</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <link rel="stylesheet" href="styleR.css">
</head>
<body>

    <h2>Reward Analytics</h2>

    <button type="button" class="backBtn" onclick="window.location.href='RewardManagerHomepage.html';">Back to Homepage</button><br><br>

    <?php if (!empty($error_msg)): ?>
        <p style="color: red; font-weight: bold;"><?php echo htmlspecialchars($error_msg); ?></p>
    <?php endif; ?>

    <form action="ImportAnalytics.php" method="POST" enctype="multipart/form-data">
        <p>
            <label>Select CSV File (rewardvolunteerdriver.csv):</label><br><br>
            <input type="file" name="csv_file" accept=".csv" required>
        </p>
        <button type="submit" name="analyze_data">Analyze Data</button>
    </form>

</body>
</html>