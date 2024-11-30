<?php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" type="text/css" href="FitnessTrackerStyle.css">
    <meta charset="UTF-8">
    <script src="FitnessTracker.js" defer></script>
    <title>Fitness Tracker</title>
</head>
<body>
<!--Page Title-->
<h1 id="pageTitle">Fitness Tracker</h1>
<form method="POST" action="FitnessTracker.php" id="goals">
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
<form method="post" action="FitnessTracker.php" id="dailySpread">
    <h3>Enter your daily exercise information</h3>
    <label for="date">Date:</label><br>
    <input type="date" id="date" name="date" required>
    <br>
    <!--To choose either cardio or weight lifting-->
    <label for="exercise">Exercise:</label><br>
    <input type="text" id="exercise" name="exercise" required>
    <br>
    <label for="duration">Duration (minutes):</label><br>
    <input type="number" id="duration" name="duration" required>
    <br>
    <!--Blanket formula to calculate net loss calories-->
    <label for="calories">Calories Burned:</label><br>
    <input type="number" id="calories" name="calories" required>
    <br>
    <input type="submit" value="Submit">
    <br>
    <a href="FitnessHistory.php" id="historyLink">View History</a>
    <!-- Display weight on a 7 day average to check losses-->
</body>
</html>