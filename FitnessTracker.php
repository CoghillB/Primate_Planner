<?php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <meta charset="UTF-8">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <title>Fitness Tracker</title>
</head>
<body>
<!--Page Title-->
<h1 id="pageTitle">Fitness Tracker</h1>

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
        <a href="FitnessHistory.php" id="historyLink">View History</a>
</div>

    <!-- Display weight on a 7 day average to check losses-->

<script src="FitnessTracker.js"></script>
</body>
</html>