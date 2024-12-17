<?php
// Start session only if not already active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Redirect if the user is not logged in
if (!isset($_SESSION['member_id'])) {
    header("Location: UserLogin.php?message=please_login");
    exit();
}

// Load the JSON file
$jsonFile = '../PHP/data.json'; // Adjust the path as needed
$data = json_decode(file_get_contents($jsonFile), true);

// Fetch the logged-in user's details
$loggedInUser = null;
foreach ($data['users'] as $user) {
    if ($user['id'] == $_SESSION['member_id']) {
        $loggedInUser = $user;
        break;
    }
}

// Store user's first and last name in variables
$fname = $loggedInUser['fname'] ?? 'User';
$lname = $loggedInUser['lname'] ?? '';
?>
