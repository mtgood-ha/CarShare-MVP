<?php
session_start();

$user_role = isset($_SESSION['role_id']) ? (int)$_SESSION['role_id'] : 0;
if ($user_role !== 4){
    header("Location: index.php");
    exit();
}

if (!isset($_SESSION['analytics_data']) || empty($_SESSION['analytics_data'])){
    header("Location: ImportAnalytics.php");
    exit();
}

$raw_data = $_SESSION['analytics_data'];

$claimed_counts = [];
$claimed_points = [];
$total_claims = 0;
$total_points_spent = 0;

foreach ($raw_data as $row){
    $desc = trim($row['description']);
    $pts = abs((int)$row['points']);

    if ($row['points'] < 0 || strpos(strtolower($desc), 'redeemed') !== false){
        if (!isset($claimed_counts[$desc])) {
            $claimed_counts[$desc] = 0;
            $claimed_points[$desc] = 0;
        }

        $claimed_counts[$desc]++;
        $claimed_points[$desc] += $pts;
        $total_claims++;
        $total_points_spent += $pts;
    }
}

arsort($claimed_counts);

if (isset($_POST['export_analytics'])){
    $filename = "Claimed_Rewards_Analytics_" . date('Y-m-d') . ".csv";

    if (ob_get_level()){
        ob_end_clean();
    }

    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="' . $filename . '"');

    $output = fopen('php://output', 'w');
    fputs($output, "\xEF\xBB\xBF");

    fputcsv($output, ['Reward Item', 'Times Claimed', 'Total Points Spent']);

    foreach ($claimed_counts as $item => $count){
        fputcsv($output, [$item, $count, $claimed_points[$item]]);
    }

    fclose($output);
    exit();
}

$chart_labels = json_encode(array_keys($claimed_counts));
$chart_values = json_encode(array_values($claimed_counts));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reward Analytics Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="styleR.css">
</head>
<body>

    <h2>Reward Analytics Dashboard</h2>

    <p>Total Items Claimed: <?php echo number_format($total_claims); ?></p>
    <p>Total Points Spent: <?php echo number_format($total_points_spent); ?> Points</p>

    <h3>Most Popular Claimed Rewards</h3>
    <div style="width: 100%; max-width: 800px; margin: 0 auto;">
        <canvas id="rewardChart"></canvas>
    </div>

    <h3>Claimed Rewards Breakdown</h3>
    <table border="1" cellpadding="8" cellspacing="0">
        <thead>
            <tr>
                <th>Reward Item</th>
                <th>Times Claimed</th>
                <th>Total Points Spent</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($claimed_counts)): ?>
                <?php foreach ($claimed_counts as $item => $count): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($item); ?></td>
                        <td><?php echo number_format($count); ?></td>
                        <td><?php echo number_format($claimed_points[$item]); ?> Points</td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="3">No redemption records found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <br><hr><br>

    <form action="" method="POST">
        <button type="submit" name="export_analytics">Export Analytics Data (CSV)</button>
    </form>
    <br>
    <a href="ImportAnalytics.php">
        <button type="button">Upload Another CSV</button>
    </a>
    <a href="RewardManagerHomepage.html">
        <button type="button">Back</button>
    </a>
    <script>
        const ctx = document.getElementById('rewardChart').getContext('2d');
        const rewardChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?php echo $chart_labels; ?>,
                datasets: [{
                    label: 'Times Claimed',
                    data: <?php echo $chart_values; ?>,
                    backgroundColor: 'rgba(54, 162, 235, 0.6)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1, precision: 0 }
                    }
                }
            }
        });
    </script>
</body>
</html>