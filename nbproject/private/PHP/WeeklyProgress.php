<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['member_id'])) {
    header("Location: ../PHP/UserLogin.php");
    exit();
}

// Load the JSON file
$jsonFile = '../PHP/data.json';
if (!file_exists($jsonFile)) {
    die("JSON file not found at: $jsonFile");
}

$data = json_decode(file_get_contents($jsonFile), true);
if ($data === null) {
    die("Failed to decode JSON: " . json_last_error_msg());
}

// Locate the logged-in user
$user = null;
foreach ($data['users'] as $u) {
    if ($u['id'] == $_SESSION['member_id']) {
        $user = $u;
        break;
    }
}
if (!$user) {
    die("User data not found for member ID: " . $_SESSION['member_id']);
}

// Get goals and exercises
$goals = $user['goals'] ?? [];
$exercises = $user['exercises'] ?? [];

// Generate a 7-day range starting from the current date
$dates = [];
for ($i = 0; $i < 7; $i++) { // Start from today and go 6 days forward
    $dates[] = date('Y-m-d', strtotime("+$i days"));
}


// Filter exercises for the last 7 days and map data for the chart
$weeklyData = [];
foreach ($dates as $date) {
    $dailyData = array_filter($exercises, fn($e) => $e['date'] === $date);

    $totalCalories = array_sum(array_column($dailyData, 'calories_burned'));
    $totalDuration = array_sum(array_column($dailyData, 'duration'));
    $avgWeight = count($dailyData) > 0 ? array_sum(array_column($dailyData, 'weight')) / count($dailyData) : null;

    $weeklyData[] = [
        'exercise_date' => $date,
        'avg_weight' => $avgWeight,
        'total_duration' => $totalDuration,
        'total_calories' => $totalCalories,
    ];
}

// Calculate progress toward goals
$totalCaloriesBurned = array_sum(array_column($weeklyData, 'total_calories'));
$totalDuration = array_sum(array_column($weeklyData, 'total_duration'));
$caloriesGoalProgress = ($goals['calories_goal'] ?? 0) > 0
    ? min(($totalCaloriesBurned / $goals['calories_goal']) * 100, 100) : 0;

$durationGoalProgress = ($goals['exercise_goal'] ?? 0) > 0
    ? min(($totalDuration / $goals['exercise_goal']) * 100, 100) : 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weekly Progress</title>
    <link rel="stylesheet" href="../CSS/WeeklyProgress.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

<!-- Navbar -->
<nav class="navbar">
    <span class="navbar-brand">Primate Planner</span>
    <div class="logout-container">
        <button class="logout-btn" onclick="window.location.href='../PHP/Logout.php'">Log Out</button>
    </div>
</nav>


<div class="text-center">
    <h1>Weekly Progress</h1>
</div>

<main class="content">
    <div id="goals">
        <?php if ($goals): ?>
            <h3 class="text-center">Progress Towards Weekly Goals</h3>
            <p>Calories Burned Goal: <?= $totalCaloriesBurned ?> / <?= $goals['calories_goal'] ?? 0 ?>
                (<?= number_format($caloriesGoalProgress, 2) ?>%)</p>
            <p>Exercise Duration Goal: <?= $totalDuration ?> minutes / <?= $goals['exercise_goal'] ?? 0 ?> minutes
                (<?= number_format($durationGoalProgress, 2) ?>%)</p>
        <?php else: ?>
            <p class="text-center">No goals have been set yet. Please set your goals in the Fitness Tracker page.</p>
        <?php endif; ?>
    </div>

    <div id="exercises">
        <canvas id="weeklyProgressChart"></canvas>
    </div>

    <div id="navButtons">
        <a href="../PHP/FitnessTracker.php" id="historyLink">Back to Fitness Tracker</a>
    </div>
</main>

<script>
    const exerciseData = <?php echo json_encode($weeklyData); ?>;

    const labels = exerciseData.map(data => data.exercise_date);
    const durations = exerciseData.map(data => data.total_duration || 0);
    const calories = exerciseData.map(data => data.total_calories || 0);
    const weights = exerciseData.map(data => data.avg_weight || null);

    const ctx = document.getElementById('weeklyProgressChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Duration (Minutes)',
                    data: durations,
                    backgroundColor: '#DEAA79',
                    borderColor: '#DEAA79',
                    borderWidth: 1
                },
                {
                    label: 'Calories Burned',
                    data: calories,
                    backgroundColor: '#659287',
                    borderColor: '#659287',
                    borderWidth: 1
                },
                {
                    label: 'Weight (lbs)',
                    data: weights,
                    type: 'line',
                    borderColor: '#FFE6A9',
                    borderWidth: 3,
                    pointBackgroundColor: '#FFE6A9',
                    pointRadius: 4,
                    fill: false
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    labels: {
                        color: '#2A3132'
                    }
                },
                title: {
                    display: true,
                    text: 'Weekly Exercise Progress',
                    color: '#2A3132'
                }
            },
            scales: {
                x: {
                    ticks: {
                        color: '#2A3132'
                    }
                },
                y: {
                    ticks: {
                        color: '#2A3132'
                    },
                    beginAtZero: true
                }
            }
        }
    });
</script>
</body>
</html>
