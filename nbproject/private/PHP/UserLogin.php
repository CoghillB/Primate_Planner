<?php
session_start();
echo "<link rel='stylesheet' type='text/css' href='../CSS/LoginStyle.css'>";

// Retrieve user input
$email = filter_input(INPUT_POST, 'username', FILTER_VALIDATE_EMAIL);
$password = filter_input(INPUT_POST, 'password');

// Load the JSON file
$jsonFile = '../PHP/data.json';
$data = json_decode(file_get_contents($jsonFile), true);

$message = "";
if (isset($_GET['message'])) {
    if ($_GET['message'] === 'account_created') {
        $message = "Account created successfully! Please log in.";
    } elseif ($_GET['message'] === 'email_in_use') {
        $message = "Email already in use. Please log in.";
    }
}

// Authenticate user
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($data['users'] as $user) {
        if ($user['email'] === $email && password_verify($password, $user['password'])) {
            $_SESSION['member_id'] = $user['id'];
            header("Location: HomePage.php");
            exit();
        }
    }
    $message = "Incorrect Username or Password.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="../CSS/LoginStyle.css">
    <title>Login</title>
</head>
<body>
<main>
    <h1 id="pageTitle">Welcome to Primate Planner</h1>
    <?php if (!empty($message)): ?>
        <div class="alert alert-info"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>
    <form method="post" action="UserLogin.php" id="loginForm" autocomplete="off">
        <h3>Login</h3>
        <label for="username">Username:</label><br>
        <input type="email" id="username" name="username" placeholder="Enter your email" required autocomplete="off">
        <br>
        <label for="password">Password:</label><br>
        <input type="password" id="password" name="password" placeholder="Enter your password" required autocomplete="new-password">
        <br>
        <button type="submit" name="submit" class="btn btn-primary">Sign In</button>
        <br>
        <a href="../HTML/CreateAccount.html" id="registerLink">Create Account</a>
    </form>
</main>
</body>
</html>
