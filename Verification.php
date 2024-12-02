<?php
session_start();

// Database connection
$username = 'root';
$password = 'letmein';
$hostname = 'localhost';
$dbname = 'Primate_Planner';
$dbhandle = new mysqli($hostname, $username, $password, $dbname);
if ($dbhandle->connect_error) {
    die('Connection failed: ' . $dbhandle->connect_error);
}

// Assume these are user input values from a form
$user_input = $_POST['username'] ?? '';
$user_password = $_POST['password'] ?? '';

// Sanitize user input
$sanitized_username = htmlspecialchars($user_input, ENT_QUOTES, 'UTF-8');

// Query to check for valid user
$query = "SELECT * FROM users WHERE username = ? AND password = ?";
$stmt = $dbhandle->prepare($query);
$stmt->bind_param('ss', $sanitized_username, $user_password); // Passwords should be hashed in production

// Execute the statement
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Successfully logged in
    $_SESSION['username'] = $sanitized_username; // Store username in session

    // Redirect to calendar
    header('Location: calendar.php');
    exit();
} else {
    echo 'Invalid login credentials!';
}

// Close statement and connection
$stmt->close();
$dbhandle->close();
?>