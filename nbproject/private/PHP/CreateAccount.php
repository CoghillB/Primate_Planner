<?php
echo "<link rel='stylesheet' type='text/css' href='../CSS/LoginStyle.css'>";

// Retrieve form data with basic sanitization
$fname = filter_input(INPUT_POST, 'fname', FILTER_SANITIZE_STRING);
$lname = filter_input(INPUT_POST, 'lname', FILTER_SANITIZE_STRING);
$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
$password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
$weight = filter_input(INPUT_POST, 'weight', FILTER_SANITIZE_NUMBER_INT);

// Load the JSON file
$jsonFile = '../PHP/data.json';
$data = json_decode(file_get_contents($jsonFile), true);

// Check if email already exists
foreach ($data['users'] as $user) {
    if ($user['email'] === $email) {
        header("Location: UserLogin.php?message=email_in_use");
        exit();
    }
}

// Hash the password securely using BCRYPT
$hashedPassword = password_hash($password, PASSWORD_BCRYPT);

// Add the new user
$newUser = [
    'id' => count($data['users']) + 1,
    'fname' => $fname,
    'lname' => $lname,
    'email' => $email,
    'password' => $hashedPassword,
    'weight' => $weight,
    'goals' => null,
    'exercises' => []
];
$data['users'][] = $newUser;

// Save the updated JSON file
file_put_contents($jsonFile, json_encode($data, JSON_PRETTY_PRINT));

// Redirect to the login page with a success message
header("Location: UserLogin.php?message=account_created");
exit();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Account</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../CSS/CreateAccount.css">
</head>
<body>
<main>
    <div class="container">
        <div class="row">
            <div class="col">
                <h1 id="pageTitle">Welcome to Primate Planner!</h1>
            </div>
        </div>
        <div class="row">
            <div class="col text-center col-12 mx-7">
                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger"><?= $error ?></div>
                <?php endif; ?>
                <?php if (!empty($success)): ?>
                    <div class="alert alert-success"><?= $success ?></div>
                <?php endif; ?>
                <form action="CreateAccount.php" method="post" id="createAccountForm">
                    <h3>Create an Account</h3>
                    <label for="fname">First Name:</label><br>
                    <input type="text" id="fname" name="fname" required><br><br>

                    <label for="lname">Last Name:</label><br>
                    <input type="text" id="lname" name="lname" required><br><br>

                    <label for="email">Email:</label><br>
                    <input type="email" id="email" name="email" required><br><br>

                    <label for="password">Password:</label><br>
                    <input type="password" id="password" name="password" required><br><br>

                    <label for="weight">Weight (lbs):</label><br>
                    <input type="number" id="weight" name="weight" required><br><br>

                    <button type="submit" class="btn btn-primary">Create Account</button>
                </form>
            </div>
        </div>
    </div>
</main>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
