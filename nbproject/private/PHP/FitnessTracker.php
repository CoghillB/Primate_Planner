<!--
This file is the main page for the Fitness Tracker application. It allows users to set their fitness goals and log their daily exercises.
The page is divided into two sections: Goals and Exercises. The Goals section allows users to set their weight, weekly calorie goal, and weekly exercise goal.
The Exercises section allows users to log their daily exercise information, including the date, current weight, exercise type, and duration.
The page also includes a link to the WeeklyProgress.php page to show the user's current weekly progress chart.
The page uses PHP to handle form submissions and interact with the database to store fitness goals and exercise information.
-->

<?php
session_start(); // Start session
require_once 'LoadUser.php';
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if the user is logged in
if (!isset($_SESSION['member_id'])) {
    die("Session error: User is not logged in. Please log in again.");
}

$jsonFile = '../PHP/data.json';

// Check if the JSON file exists and is readable
if (!file_exists($jsonFile) || !is_readable($jsonFile)) {
    die("Error: Cannot access JSON file at $jsonFile.");
}

// Load the current JSON data
$data = json_decode(file_get_contents($jsonFile), true);
if ($data === null) {
    die("Error: JSON decoding failed: " . json_last_error_msg());
}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if Goals form was submitted
    if (isset($_POST['weight'], $_POST['caloriesGoal'], $_POST['exercise'])) {
        // Fetch submitted goals data
        $goalsData = [
            'weight' => intval($_POST['weight']),
            'calories_goal' => intval($_POST['caloriesGoal']),
            'exercise_goal' => intval($_POST['exercise'])
        ];

        // Locate the user in the JSON file
        $member_id = $_SESSION['member_id'];
        $userIndex = null;
        foreach ($data['users'] as $index => $user) {
            if ($user['id'] == $member_id) {
                $userIndex = $index;
                break;
            }
        }

        // Error if user not found
        if ($userIndex === null) {
            die("Error: User not found in the data file.");
        }

        // Replace existing goals with new goals data
        $data['users'][$userIndex]['goals'] = $goalsData;

        // Save updated data back to JSON file
        if (file_put_contents($jsonFile, json_encode($data, JSON_PRETTY_PRINT)) === false) {
            die("Error: Failed to update goals in the data file.");
        } else {
            header("Location: FitnessTracker.php?status=goals_updated");
            exit();
        }
    }

    // Check if Exercise form was submitted (existing logic)
    if (isset($_POST['duration'], $_POST['calories_burned'], $_POST['date'])) {
        $exerciseData = [
            'date' => $_POST['date'],
            'calories_burned' => intval($_POST['calories_burned']),
            'duration' => intval($_POST['duration']),
            'weight' => isset($_POST['dailyWeight']) ? intval($_POST['dailyWeight']) : null,
            'exercise_type' => $_POST['exercise'] ?? 'unknown',
        ];

        // Locate the user
        $member_id = $_SESSION['member_id'];
        $userIndex = null;
        foreach ($data['users'] as $index => $user) {
            if ($user['id'] == $member_id) {
                $userIndex = $index;
                break;
            }
        }

        if ($userIndex === null) {
            die("Error: User with ID $member_id not found in JSON.");
        }

        // Check if the date already exists
        $existingIndex = null;
        foreach ($data['users'][$userIndex]['exercises'] as $index => $exercise) {
            if ($exercise['date'] === $exerciseData['date']) {
                $existingIndex = $index;
                break;
            }
        }

        // Replace or append exercise data
        if ($existingIndex !== null) {
            $data['users'][$userIndex]['exercises'][$existingIndex] = $exerciseData;
        } else {
            $data['users'][$userIndex]['exercises'][] = $exerciseData;
        }

        // Save the updated data back to the JSON file
        if (file_put_contents($jsonFile, json_encode($data, JSON_PRETTY_PRINT)) === false) {
            die("Failed to write data to JSON file.");
        } else {
            header("Location: FitnessTracker.php?status=exercise_updated");
            exit();
        }
    }
}
?>






<!DOCTYPE html>
<html lang="en">
<head>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <meta charset="UTF-8">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="../CSS/FitnessTracker.css">
    <title>Fitness Tracker</title>
</head>
<body>
<!-- Navbar -->
<nav class="navbar navbar-dark bg-dark fixed-top">
    <div class="container-fluid">
        <!-- Home Button -->
        <button class="btn btn-outline-light" onclick="window.location.href='Homepage.php'">Home</button>

        <!-- Centered Navbar Title -->
        <span class="navbar-brand mx-auto text-center flex-grow-1"><?php echo htmlspecialchars($fname) . ' ' . htmlspecialchars($lname); ?></span>

        <!-- Logout Button -->
        <form action="../PHP/Logout.php" method="POST">
            <button type="submit" class="btn btn-outline-light logout-btn">Log Out</button>
        </form>
    </div>
</nav>

<h1 class="text-center">Fitness Tracker</h1>
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
            <input type="hidden" id="hiddenCaloriesBurned" name="calories_burned" value="0">
            <br>
            <input type="submit" value="Submit">
            <br>
            <!--Bring to chart-->
            <a href="WeeklyProgress.php" id="historyLink" class="btn btn-secondary mt-3">Current Weekly Progress</a>
        </form>
    </div>
</div>

<script src="../JavaScript/FitnessTracker.js"></script>
</body>
</html>
