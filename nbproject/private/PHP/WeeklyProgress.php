<?php
session_start();
//echo "Logged-in member ID: " . ($_SESSION['member_id'] ?? 'Not Logged In') . "<br>";

// Enable debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Redirect to login if not logged in
if (!isset($_SESSION['member_id'])) {
    header("Location: Login.html");
    exit();
}

$mid = $_SESSION['member_id'];

// Database connection
$conn = new mysqli('localhost', 'root', 'letmein', 'Primate_Planner');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch goals for the user
$goalsQuery = "SELECT weight_goal, weekly_calories, weekly_duration FROM Goals WHERE mid = $mid";
$goalsResult = $conn->query($goalsQuery);
$goalsData = $goalsResult->fetch_assoc();

// Generate 7-day date range (current day to 6 days ago)
$dates = [];
for ($i = 0; $i < 7; $i++) {
    $dates[] = date('Y-m-d', strtotime("+$i days"));
}

// Fetch weekly exercise data for the user

$exerciseQuery = "
    WITH date_range AS (
    SELECT CURDATE() + INTERVAL n DAY AS exercise_date
    FROM (SELECT 0 AS n UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 
          UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6) AS days
)
SELECT 
    d.exercise_date, 
    COALESCE(AVG(e.weight), NULL) AS avg_weight, 
    COALESCE(SUM(e.duration_minutes), 0) AS total_duration, 
    COALESCE(SUM(e.calories_burned), 0) AS total_calories
FROM 
    date_range d
LEFT JOIN 
    daily_exercises e
ON 
    d.exercise_date = e.exercise_date AND e.mid = ?
GROUP BY 
    d.exercise_date
ORDER BY 
    d.exercise_date ASC;
";

$stmt = $conn->prepare($exerciseQuery);
$stmt->bind_param("i", $mid); // Use the logged-in member's ID
$stmt->execute();
$exerciseResult = $stmt->get_result();

// Map results to an array keyed by date
$exerciseData = [];
while ($row = $exerciseResult->fetch_assoc()) {
    $exerciseData[$row['exercise_date']] = $row;
}
$stmt->close();

// Merge with 7-day date range, filling in missing days with zeros
$finalData = [];
foreach ($dates as $date) {
    if (array_key_exists($date, $exerciseData)) {
        // Use data from exerciseData if available
        $finalData[] = $exerciseData[$date];
    } else {
        // Fill with zero values if no data for the date
        $finalData[] = [
            'exercise_date' => $date,
            'avg_weight' => null,
            'total_duration' => 0,
            'total_calories' => 0,
        ];
    }
}

// Calculate progress towards goals
$totalCaloriesBurned = array_sum(array_column($finalData, 'total_calories'));
$totalDuration = array_sum(array_column($finalData, 'total_duration'));
$caloriesGoalProgress = ($goalsData && $goalsData['weekly_calories']) ? min(($totalCaloriesBurned / $goalsData['weekly_calories']) * 100, 100) : 0;
$durationGoalProgress = ($goalsData && $goalsData['weekly_duration']) ? min(($totalDuration / $goalsData['weekly_duration']) * 100, 100) : 0;

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weekly Progress Chart</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
<div class="container mt-5">
    <h1 class="text-center mb-4">Weekly Progress Chart</h1>
    <?php if ($goalsData): ?>
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <h5 class="card-title">Progress Towards Weekly Goals</h5>
                <p class="card-text">
                    Calories Burned Goal: <?= htmlspecialchars($totalCaloriesBurned) ?>
                    / <?= htmlspecialchars($goalsData['weekly_calories']) ?>
                    (<?= number_format($caloriesGoalProgress, 2) ?>%)<br>
                    Exercise Duration Goal: <?= $totalDuration ?> minutes / <?= $goalsData['weekly_duration'] ?> minutes
                    (<?= number_format($durationGoalProgress, 2) ?>%)
                </p>
            </div>
        </div>
    <?php else: ?>
        <p>No goals have been set yet. Please set your goals in the Fitness Tracker page.</p>
    <?php endif; ?>
</div>
<div class="container mt-5">
    <div class="card shadow-sm">
        <div class="card-body">
            <canvas id="weeklyProgressChart"></canvas>
        </div>
    </div>
</div>
<div class="container text-center mt-4">
    <form action="FitnessTracker.php" method="get">
        <button type="submit" class="btn btn-primary">Back to Fitness Tracker</button>
    </form>
</div>
<script>
    const exerciseData = <?php echo json_encode($finalData); ?>;

    // Debug in browser
    console.log("Chart Data:", exerciseData);

    const labels = exerciseData.map(data => data.exercise_date);
    const durations = exerciseData.map(data => data.total_duration || 0);
    const calories = exerciseData.map(data => data.total_calories || 0);
    const weights = exerciseData.map(data => data.avg_weight || null);

    // Debug in browser
    console.log("Labels:", labels);
    console.log("Durations:", durations);
    console.log("Calories:", calories);
    console.log("Weights:", weights);


    // Create the chart
    const ctx = document.getElementById('weeklyProgressChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Duration (Minutes)',
                    data: durations,
                    backgroundColor: 'rgba(75, 192, 192, 0.5)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1,
                    yAxisID: 'y-duration'
                },
                {
                    label: 'Calories Burned',
                    data: calories,
                    backgroundColor: 'rgba(255, 99, 132, 0.5)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1,
                    yAxisID: 'y-calories'
                },
                {
                    label: 'Weight (lbs)',
                    data: weights,
                    type: 'line',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 3,
                    pointBackgroundColor: 'rgba(54, 162, 235, 1)',
                    pointRadius: 4,
                    fill: false,
                    yAxisID: 'y-weight'
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        font: {
                            size: 14
                        }
                    }
                },
                title: {
                    display: true,
                    text: 'Weekly Exercise Progress',
                    font: {
                        size: 18
                    }
                }
            },
            scales: {
                'y-duration': {
                    type: 'linear',
                    position: 'left',
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Duration (Minutes)'
                    }
                },
                'y-calories': {
                    type: 'linear',
                    position: 'right',
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Calories Burned'
                    },
                    grid: {
                        drawOnChartArea: false
                    }
                },
                'y-weight': {
                    type: 'linear',
                    position: 'right',
                    beginAtZero: false,
                    title: {
                        display: true,
                        text: 'Weight (lbs)'
                    },
                    grid: {
                        drawOnChartArea: false
                    }
                }
            }
        }
    });
</script>
</body>
</html>
