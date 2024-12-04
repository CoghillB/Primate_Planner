<?php
// debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

//  connection
$conn = new mysqli('localhost', 'cs213user', 'letmein', 'fitnesstracker');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Start session/ set member ID test
session_start();
$_SESSION['member_id'] = 1;
$mid = $_SESSION['member_id'] ?? null;
if (!$mid) {
    die("You must be logged in to view weekly progress.");
}

// Fetch weekly exercise data
$result = $conn->query("SELECT exercise_date, AVG(weight) AS avg_weight, SUM(duration_minutes) AS total_duration, SUM(calories_burned) AS total_calories
                        FROM daily_exercises
                        WHERE mid = $mid AND YEARWEEK(exercise_date, 1) = YEARWEEK(CURDATE(), 1)
                        GROUP BY exercise_date
                        ORDER BY exercise_date");

// Store fetched data into an array
$exerciseData = [];
while ($row = $result->fetch_assoc()) {
    $exerciseData[] = $row;
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Import Bootstrap for styling an Chart.js  -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <meta charset="UTF-8">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <title>Weekly Progress Chart</title>
</head>
<body>
    <!-- Container  chart -->
    <div class="container mt-5">
        <h1 class="text-center mb-4">Weekly Progress Chart</h1>
        <div class="card shadow-sm">
            <div class="card-body">
                <canvas id="weeklyProgressChart"></canvas>
            </div>
        </div>
    </div>
    <!-- Back Button to FitnessTracker -->
    <div class="container text-center mt-4">
        <form action="FitnessTracker.php" method="get">
            <button type="submit" class="btn btn-primary">Back to Fitness Tracker</button>
        </form>
    </div>

    <script>
        // Pass the PHP for chart 
        const exerciseData = <?php echo json_encode($exerciseData); ?>;

        // Prep data for the chart
        let labels = [];
        let durations = [];
        let calories = [];
        let weights = [];

        // Incorperate exercise data into labels and arrays
        exerciseData.forEach(day => {
            labels.push(day.exercise_date);
            durations.push(day.total_duration);
            calories.push(day.total_calories);
            weights.push(day.avg_weight);
        });

        // Create the chart with Chart.js library
        const ctx = document.getElementById('weeklyProgressChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels, 
                datasets: [
                    {
                        //Duration
                        label: 'Duration (Minutes)',
                        data: durations,
                        backgroundColor: 'rgba(75, 192, 192, 0.5)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1,
                        yAxisID: 'y-duration' 
                    },
                    {
                        //Calories Burnt
                        label: 'Calories Burned',
                        data: calories,
                        backgroundColor: 'rgba(255, 99, 132, 0.5)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1,
                        yAxisID: 'y-calories' 
                    },
                    {
                        //Weight LINED instead of bars
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
                            //avoid overlapp
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
                            //avoid overlap
                            drawOnChartArea: false 
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>