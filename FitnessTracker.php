<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$conn = new mysqli('localhost', 'cs213user', 'letmein', 'fitnesstracker');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

session_start();
$_SESSION['member_id'] = 1;
// Does member ID exist
$mid = $_SESSION['member_id'] ?? null; 
if (!$mid) {
    die("You must be logged in to set fitness goals.");
}

// Check if a form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['weight'], $_POST['caloriesGoal'], $_POST['exercise'])) {
        // Handle Goals Form Submission
        $weight_goal = (int) $_POST['weight'];
        $weekly_calories = (int) $_POST['caloriesGoal'];
        $weekly_duration = (int) $_POST['exercise'];

        // Insert or update fitness goals
        $stmt = $conn->prepare("INSERT INTO Goals (mid, weight_goal, weekly_calories, weekly_duration, created_at)
                                VALUES (?, ?, ?, ?, NOW())
                                ON DUPLICATE KEY UPDATE
                                weight_goal = VALUES(weight_goal),
                                weekly_calories = VALUES(weekly_calories),
                                weekly_duration = VALUES(weekly_duration)");
        $stmt->bind_param("iiii", $mid, $weight_goal, $weekly_calories, $weekly_duration);

        if (!$stmt->execute()) {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    } elseif (isset($_POST['date'], $_POST['dailyWeight'], $_POST['exercise'], $_POST['duration'])) {
        // Handle Exercise Form Submission
        $exercise_date = $_POST['date'];
        $daily_weight = (int) $_POST['dailyWeight'];
        $exercise_type = $_POST['exercise'];
        $duration_minutes = (int) $_POST['duration'];

        $workOutValues = [
            'cardio' => 7.0,
            'weightLifting' => 4.0,
            'hybrid' => 5.5
        ];
        $effortValue = $workOutValues[strtolower($exercise_type)] ?? 0;
        $weightInKg = $daily_weight * 0.453592;
        $calories_burned = $effortValue * 0.0175 * $weightInKg * $duration_minutes;

        // Update the member's weight in the Members table
        $stmt = $conn->prepare("UPDATE Members SET weight = ? WHERE mid = ?");
        $stmt->bind_param("ii", $daily_weight, $mid);
        if (!$stmt->execute()) {
            echo "Error updating weight: " . $stmt->error;
        }
        $stmt->close();

        // Update the Goals table by subtracting the duration and calories burned
        $stmt = $conn->prepare("UPDATE Goals 
                                SET weekly_calories = GREATEST(weekly_calories - ?, 0),
                                    weekly_duration = GREATEST(weekly_duration - ?, 0)
                                WHERE mid = ?");
        $stmt->bind_param("iii", $calories_burned, $duration_minutes, $mid);
        if (!$stmt->execute()) {
            echo "Error logging exercise: " . $stmt->error;
        }
        $stmt->close();

        // Insert or update daily exercise into `daily_exercises` table
        $stmt = $conn->prepare("INSERT INTO daily_exercises (mid, exercise_date, weight, exercise_type, duration_minutes, calories_burned)
                                VALUES (?, ?, ?, ?, ?, ?)
                                ON DUPLICATE KEY UPDATE
                                weight = VALUES(weight),
                                exercise_type = VALUES(exercise_type),
                                duration_minutes = VALUES(duration_minutes),
                                calories_burned = VALUES(calories_burned)");
        $stmt->bind_param("isssid", $mid, $exercise_date, $daily_weight, $exercise_type, $duration_minutes, $calories_burned);
        if (!$stmt->execute()) {
            echo "Error logging exercise: " . $stmt->error;
        }
        $stmt->close();

        // Redirect to the WeeklyProgress.php page to show the chart
        header("Location: WeeklyProgress.php");
        exit();
    }
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <meta charset="UTF-8">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="FitnessTracker.css">
    <title>Fitness Tracker</title>
</head>
<body>
<!-- Navbar -->
    <nav class="navbar navbar-dark bg-dark fixed-top">
        <div class="container-fluid">
            <span class="navbar-brand mx-auto text-center flex-grow-1">Fitness Tracker</span>
            <form action="UserLogin.php" method="POST">
            <button type="submit" class="btn btn-outline-light">Log Out</button>
        </form>
        </div>
    </nav>
<div class="content">
    <!--Nav Buttons-->
<div class="container" id="navButtons">
    <button id="showGoals">Goals</button>
    <button id="showExercises">Exercises</button>
</div>
<!--Container for goal form-->
<div class="container" id="goals" style="display: none;">
    <form method="POST" action="FitnessTracker.php" id="goalsForm">
        <h3>Enter your goals</h3>
        <label for="weight">Weight (lbs):</label><br>
        <input type="number" id="weight" name="weight" required>
        <br>
        <!--Calories Goal Adjustable to either weekly-->
        <label for="calories">Weekly Calorie Goal:</label><br>
        <input type="number" id="caloriesGoal" name="caloriesGoal" required>
        <br>
        <!--Changable exercise goals, we could do weekly-->
        <label for="exercise">Weekly Exercise Goal (minutes):</label><br>
        <input type="number" id="exercise" name="exercise" required>
        <br>
        <input type="submit" value="Submit">
    </form>
</div>
    <!--container for exercise form-->
    <div class="container" id="exercises" style="display: none;">
        <form method="post" action="FitnessTracker.php" id="dailySpread">
            <h3>Enter your daily exercise information</h3>
            <label for="date">Date:</label><br>
            <input type="date" id="date" name="date" required>
            <br>
            <label for="dailyWeight">Current Weight (lbs):</label>
            <br>
            <input type="number" id="dailyWeight" name="dailyWeight" required>
            <br>
            <!--To choose either cardio or weight lifting-->
            <label for="exercise">Exercise:</label><br>
            <select id="exerciseChoice" name="exercise" required>
                <option value="cardio">Cardio</option>
                <option value="weightLifting">Weightlifting</option>
                <option value="hybrid">Hybrid</option>
            </select>
            <br>
            <label for="duration">Duration (minutes):</label><br>
            <input type="number" id="duration" name="duration" required>
            <br>
            <!--Blanket formula to calculate net loss calories-->
            <label for="calories">Calories Burned:</label><br>
            <output id="caloriesBurned">0</output>
            <br>
            <input type="submit" value="Submit">
            <br>
            <!--Bring to chart-->
            <a href="WeeklyProgress.php" id="historyLink" class="btn btn-secondary mt-3">Current Weekly Progress</a>
        </form>
    </div>    
</div>

<script src="FitnessTracker.js"></script>
</body>
</html>
