<?php
session_start();
require_once 'LoadUser.php';
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

// Fetch the user's initial weight (from goals or first recorded weight)
$initialWeight = $user['weight'] ?? null;
// If initial weight is not set, fallback to the first exercise entry's weight
if (!$initialWeight) {
    foreach ($exercises as $exercise) {
        if (isset($exercise['weight']) && is_numeric($exercise['weight'])) {
            $initialWeight = $exercise['weight'];
            break;
        }
    }
}
//$weightGoal = $goals['weight'] ?? 150;
// Determine the user's current weight (latest weight from exercises or default to user's weight)
$currentWeight = $user['weight'] ?? null;

// If exercises exist, find the latest weight from exercises
foreach (array_reverse($exercises) as $exercise) {
    if (isset($exercise['weight']) && is_numeric($exercise['weight'])) {
        $currentWeight = $exercise['weight'];
        break;
    }
}

// Calculate progress toward the weight goal
$weightGoal = $goals['weight'] ?? 150; // Weight goal from goals
$weightProgress = null;
$weightRemaining = null;

if ($currentWeight && $weightGoal) {
    $totalWeightToLose = abs($currentWeight - $weightGoal);
    $weightRemaining = max($currentWeight - $weightGoal, 0); // How much weight is left
    $weightProgress = (($totalWeightToLose - $weightRemaining) / $totalWeightToLose) * 100;

    if ($weightProgress > 100) $weightProgress = 100; // Cap progress at 100%
}

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
    <div class="home-container">
        <button class="logout-btn" onclick="window.location.href='Homepage.php'">Home</button>
    </div>
    <span class="navbar-brand"><?php echo htmlspecialchars($fname) . ' ' . htmlspecialchars($lname); ?></span>
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

            <?php if (isset($weightGoal) && isset($currentWeight)): ?>
                <p>Weight Goal: <?= $currentWeight ?> lbs / <?= $weightGoal ?> lbs
                    (<?= number_format($weightRemaining, 1) ?> lbs left to go!!!)</p>
            <?php else: ?>
                <p>Weight Goal: No weight goal has been set yet.</p>
            <?php endif; ?>

        <?php else: ?>
            <p class="text-center">No goals have been set yet. Please set your goals in the Fitness Tracker page.</p>
        <?php endif; ?>
    </div>


    <div id="exercises" style="width: 100%; margin: auto;">
        <canvas id="weeklyProgressChart"></canvas>
    </div>

    <div id="navButtons">
        <a href="../PHP/FitnessTracker.php" id="historyLink">Back to Fitness Tracker</a>
    </div>
</main>

<script>

    const exerciseData = <?php echo json_encode($weeklyData); ?>;
    const weightGoal = <?= json_encode($weightGoal); ?>;
    const labels = exerciseData.map(data => data.exercise_date);
    const durations = exerciseData.map(data => data.total_duration || 0);
    const calories = exerciseData.map(data => data.total_calories || 0);


    const weights = exerciseData.map(data => data.avg_weight || null);
    console.log("Weights Data:", weights);
    const ctx = document.getElementById('weeklyProgressChart').getContext('2d');

    // Calculate the min and max range for the weight axis
    const weightMin = weightGoal - 50;
    const weightMax = weightGoal + 50;

    const chart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels, // Dates for X-axis
            datasets: [
                {
                    label: 'Duration (Minutes)',
                    data: durations,
                    backgroundColor: '#DEAA79',
                    borderColor: '#DEAA79',
                    borderWidth: 1,
                    yAxisID: 'y-left' // Left Y-axis
                },
                {
                    label: 'Calories Burned',
                    data: calories,
                    backgroundColor: '#659287',
                    borderColor: '#659287',
                    borderWidth: 1,
                    yAxisID: 'y-right' // Right Y-axis for Calories
                },
                {
                    label: 'Weight (lbs)',
                    data: weights,
                    type: 'line',
                    borderColor: '#FFE6A9',
                    borderWidth: 3,
                    pointBackgroundColor: '#FFE6A9',
                    pointRadius: 4,
                    fill: false,
                    yAxisID: 'y-weight' // Separate Y-axis for Weight
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
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
                    id: 'y-left',
                    position: 'left',
                    title: {
                        display: true,
                        text: 'Duration (Minutes)',
                        color: '#2A3132'
                    },
                    beginAtZero: true,
                    ticks: {
                        color: '#2A3132'
                    }
                },
                'y-right': {
                    position: 'right',
                    title: {
                        display: true,
                        text: 'Calories Burned',
                        color: '#659287'
                    },
                    beginAtZero: true,
                    grid: {
                        drawOnChartArea: false
                    },
                    ticks: {
                        color: '#659287'
                    }
                },
                'y-weight': {
                    position: 'right',
                    title: {
                        display: true,
                        text: 'Weight (lbs)',
                        color: '#FFE6A9'
                    },
                    min: weightGoal - 30, // Force minimum range
                    max: weightGoal + 30, // Force maximum range
                    grid: {
                        drawOnChartArea: false // Prevent grid overlap
                    },
                    ticks: {
                        color: '#FFE6A9'
                    }
                }
            }
        }
    });


    // Force resize on window resize
    window.addEventListener('resize', () => {
        chart.resize();
    });


</script>
</body>
</html>
